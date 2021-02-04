namespace SabaNews.Site.Controllers
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Constants;
    using SabaNews.Models;
    using SabaNews.Models.Comment;
    using SabaNews.Models.ViewModels;
    using SabaNews.Services.Interfaces;
    using System;
    using System.Threading.Tasks;

    public class CommentController : Controller
    {
        private const bool isLogin = true;
        private readonly ICommentService commentService;

        public CommentController(ICommentService commentService)
        {
            this.commentService = commentService;
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
                IsLogin = isLogin
            };
            return PartialView("_Comments", Model);
        }

        [HttpPost]
        [Route("/comments/create")]
        public async Task<IActionResult> CreateComment(int postID, int parent, string commentContent)
        {
            var remoteIpAddress = HttpContext.Connection.RemoteIpAddress.ToString();

            var model = new CreateComment
            {
                CommentPostID = postID,
                CommentAuthor = "Hank Nguyen",
                CommentAuthorEmail = "hank.nguyen@starixsoft.com",
                CommentAuthorIP = remoteIpAddress,
                CommentDate = DateTime.Now,
                CommentDateGMT = DateTime.UtcNow,
                CommentContent = commentContent,
                CommentAgent = Request.Headers["User-Agent"].ToString(),
                CommentParent = parent,
                UserID = 2
            };
            var result = await commentService.CreateComment(model);
            model.CommentID = result;
            return Json(new { isSuccessful = result != 0, comment = model });
        }

        [HttpPost]
        [Route("/comments/like")]
        public async Task<IActionResult> Like(long commentID, string type)
        {
            var remoteIpAddress = HttpContext.Connection.RemoteIpAddress.ToString();
            var commandResult = await commentService.UpdateLikeData(commentID, remoteIpAddress, type);
            return Json(commandResult);
        }


    }
}