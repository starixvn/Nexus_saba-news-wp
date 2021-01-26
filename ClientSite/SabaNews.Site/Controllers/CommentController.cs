namespace SabaNews.Site.Controllers
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Constants;
    using SabaNews.Models;
    using SabaNews.Models.ViewModels;
    using SabaNews.Services.Interfaces;
    using System;
    using System.Threading.Tasks;

    public class CommentController : Controller
    {
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

        [Route("/comments/paging")]
        public async Task<IActionResult> GetComments(int postID, int page)
        {
            var start = (page - 1) * Constants.Perpage;
            var commentTemplate = await commentService.GetCommentsAsync(postID, start, Constants.Perpage);
            var Data = new ViewData
            {
                CurrentPage = page,
                PageSize = Constants.Perpage,
                TotalPages = (int)Math.Ceiling(commentTemplate.TotalParentCmt / (double)Constants.Perpage)
            };
            var Model = new CommentViewModel
            {
                CommentTemplate = commentTemplate,
                ViewData = Data
            };
            return PartialView("_Comments", Model);

        }
    }
}
