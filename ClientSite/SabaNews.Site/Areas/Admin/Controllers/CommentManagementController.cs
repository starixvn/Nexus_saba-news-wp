namespace SabaNews.Site.Areas.Admin.Controllers
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Criteria.Comment;
    using SabaNews.Models.Comment;
    using SabaNews.Services.Interfaces;
    using System.Threading.Tasks;
    using SabaNews.Core.Constants;
    using SabaNews.Models.ViewModel.Comment;
    using SabaNews.Core.Models;
    using System;

    public class CommentManagementController : AdminController
    {
        private readonly ICommentService commentService;

        public CommentManagementController(ICommentService commentService)
        {
            this.commentService = commentService;
        }

        public IActionResult MembershipEvent()
        {
            return View();
        }

        public async Task<ActionResult> GetDataComment(CommentFilterModel model)
        {
            var criteria = new CmtGetBySlugCriteria
            {
                Slug = Constants.MembershipEventSlug,
                PostType = Constants.pageType,
                Start = (model.Page - 1) * model.PageSize,
                End = model.PageSize
            };
            var comments = await commentService.GetCommentBySlug(criteria);
            var viewModel = new CommentGridViewModel
            {
                Comments = comments,
                Paging = new PagingModel
                {
                    CurrentPage = model.Page,
                    PageSize = model.PageSize,
                    TotalPages = (int)Math.Ceiling(criteria.TotalItems / (double)model.PageSize),
                }
            };

            return PartialView("_MembershipEventDataGrid", viewModel);
        }
    }
}
