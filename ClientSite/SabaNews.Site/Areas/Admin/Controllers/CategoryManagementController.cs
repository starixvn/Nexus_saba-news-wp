namespace SabaNews.Site.Areas.Admin.Controllers
{
    using System.Threading.Tasks;
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Services;
    using SabaNews.Criteria.Category;
    using SabaNews.Livescore.Services;
    using SabaNews.Models.Categories;
    using SabaNews.Services.Interfaces;

    public class CategoryManagementController : AdminController
    {
        private readonly IDataControlService dataControlService;
        private readonly ICategoryService categoryService;
        private readonly ILeagueService leagueService;
        private readonly ISessionService sessionService;
        public CategoryManagementController(
            IDataControlService dataControlService,
            ICategoryService categoryService,
            ILeagueService leagueService,
            ISessionService sessionService)
        {
            this.dataControlService = dataControlService;
            this.categoryService = categoryService;
            this.leagueService = leagueService;
            this.sessionService = sessionService;
        }

        public async Task<ActionResult> Index()
        {
            var menus = await dataControlService.GetMenuData(MenuType.DetailMenu);
            var cates = await categoryService.GetCateLivescoreAsync();
            var leagues = await leagueService.GetLeaguesAsync();
            var model = new CateLivescoreManageViewModel
            {
                Menus = menus,
                Cates = cates,
                Leagues = leagues
            };
            return View(model);
        }

        [HttpPost]
        public async Task<ActionResult> UpdateCate(UpdateCateLivescoreCommand model)
        {
            model.ActorId = sessionService.User.Id;
            var result = await categoryService.UpdateCateLivescoreAsync(model);
            return Json(result);
        }
    }
}