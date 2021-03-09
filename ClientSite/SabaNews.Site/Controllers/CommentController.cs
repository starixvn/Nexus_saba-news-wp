namespace SabaNews.Site.Controllers
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Services;
    using SabaNews.Models;
    using SabaNews.Models.Comment;
    using SabaNews.Models.ViewModels;
    using SabaNews.Services.Interfaces;
    using System;
    using System.Linq;
    using System.Net;
    using System.Threading.Tasks;

    public class CommentController : Controller
    {
        private readonly ICommentService commentService;
        private readonly ISessionService sessionService;

        public CommentController(ICommentService commentService, ISessionService sessionService)
        {
            this.commentService = commentService;
            this.sessionService = sessionService;
        }


        [Route("/comments/getchildren")]
        public async Task<IActionResult> GetChildrenComment(int postID, int commentID)
        {
            var comments = await commentService.GetCommentChildrenAsync(postID, commentID);
            return Json(new { data = comments });
        }

        [Route("/comments")]
        public async Task<IActionResult> GetComments(int postID, string filterType, int page)
        {
            var start = (page - 1) * Constants.Perpage;
            var commentTemplate = await commentService.GetCommentsAsync(postID, filterType, start, Constants.Perpage);
            var Data = new ViewData
            {
                CurrentPage = page,
                PageSize = Constants.Perpage,
                TotalPages = (int)Math.Ceiling(commentTemplate.TotalParentCmt / (double)Constants.Perpage)
            };
            var Model = new CommentViewModel
            {
                CommentTemplate = commentTemplate,
                ViewData = Data,
                FilterType  = filterType,
                IsLogin = sessionService.User != null
            };
            return PartialView("_Comments", Model);
        }

        [HttpPost]
        [Route("/comments/create")]
        public async Task<IActionResult> CreateComment(int postID, int parent, string commentContent)
        {
            var isAuthenticated = sessionService.User != null;
            if (!isAuthenticated) {
                return Json(new { isSuccessful = false });
            }
            var remoteIpAddress = GetRemoteIpAddress();
            
            var model = new CreateComment
            {
                CommentPostID = postID,
                CommentAuthor = sessionService.User.Name,
                CommentAuthorEmail = sessionService.User.Email,
                CommentAuthorIP = remoteIpAddress,
                CommentDate = DateTime.Now,
                CommentDateGMT = DateTime.UtcNow,
                CommentContent = commentContent,
                CommentAgent = Request.Headers["User-Agent"].ToString(),
                CommentParent = parent,
                UserID = sessionService.User.Id
            };
            var result = await commentService.CreateCommentAsync(model);
            model.CommentID = result;
            return Json(new { isSuccessful = result != 0, comment = model });
        }

        [HttpPost]
        [Route("/comments/like")]
        public async Task<IActionResult> Like(long commentID, string type)
        {
            var isAuthenticated = sessionService.User != null;
            if (!isAuthenticated)
            {
                return Json(new { isSuccessful = false });
            }
            var remoteIpAddress = GetRemoteIpAddress();
            var commandResult = await commentService.UpdateLikeDataAsync(commentID, remoteIpAddress, type);
            return Json(commandResult);
        }

        public string GetRemoteIpAddress()
        {
            IPAddress remoteIpAddress = HttpContext.Connection.RemoteIpAddress;
            string ip = "";
            if (remoteIpAddress != null)
            {
                // If we got an IPV6 address, then we need to ask the network for the IPV4 address 
                // This usually only happens when the browser is on the same machine as the server.
                if (remoteIpAddress.AddressFamily == System.Net.Sockets.AddressFamily.InterNetworkV6)
                {
                    remoteIpAddress = System.Net.Dns.GetHostEntry(remoteIpAddress).AddressList
            .First(x => x.AddressFamily == System.Net.Sockets.AddressFamily.InterNetwork);
                }
                ip = remoteIpAddress.ToString();
            }
            return ip;
        }
    }
}