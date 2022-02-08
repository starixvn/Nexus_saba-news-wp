namespace SabaNews.Site.Areas.Admin.Controllers
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Helper;
    using SabaNews.Core.Models;
    using SabaNews.Criteria.User;
    using SabaNews.Models.ViewModel.User;
    using SabaNews.Services.Interfaces;
    using System;
    using System.Threading.Tasks;

    public class UserController : AdminController
    {
        private readonly IUserService userSerive;

        public UserController(IUserService userSerive)
        {
            this.userSerive = userSerive;
        }
        public ActionResult Index()
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
            var users = await userSerive.GetUserReportAsync(criteria);
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

        public ActionResult MembershipEvent()
        {
            return View();
        }

        public async Task<ActionResult> GetMembershipEventData(UserFilterMembershipEventModel model)
        {
            var criteria = new GetMembershipEventDataCriteria
            {
                Start = (model.Page - 1) * model.PageSize,
                End = model.PageSize,
                Search = model.Search,
                DateSort = model.DateSort,
                ToDate = DateTimeUtilize.TransformDatetimeToGMT(new DateTime(2022, 2, 27))
            };
            var users = await userSerive.GetUserMembershipEventDataAsync(criteria);
            var viewModel = new MembershipEventGridViewModel
            {
                Users = users,
                DateSort = model.DateSort,
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
