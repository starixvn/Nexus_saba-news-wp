namespace SabaNews.Site.Areas.Admin.Controllers
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Models;
    using SabaNews.Criteria.Auth;
    using SabaNews.Criteria.User;
    using SabaNews.Models.ViewModel.User;
    using SabaNews.Services.Interfaces;
    using System;
    using System.Threading.Tasks;

    public class UserController : AdminController
    {
        private readonly IProfileService profileService;

        public UserController(IProfileService profileService)
        {
            this.profileService = profileService;
        }
        public async Task<ActionResult> Index()
        {
            DateTime now = DateTime.Now;
            UserFilterViewModel viewModel = new()
            {
                FromDate = new DateTime(now.Year, now.Month, 1),
                ToDate = now
            };
            return View(viewModel);
        }

        [HttpPost]
        public async Task<ActionResult> GetDataUser(UserFilterViewModel param)
        {
            var criteria = new GetUserReportCriteria
            {
                FromDate = param.FromDate,
                ToDate = param.ToDate,
                Search = param.Search ??= "",
                DateSort = param.DateSort,
                Start = (param.Page - 1) * param.PageSize,
                End = param.PageSize
            };
            var users = await profileService.GetUserReportAsync(criteria);
            var model = new UserGridViewModel
            {
                Users = users,
                DateSort = param.DateSort,
                TotalItems = criteria.TotalItems,
                Paging = new PagingModel
                {
                    CurrentPage = param.Page,
                    PageSize = param.PageSize,
                    TotalPages = (int)Math.Ceiling(criteria.TotalItems / (double)param.PageSize),
                }
            };
            return PartialView("_UserDataGrid", model);
        }
    }
}
