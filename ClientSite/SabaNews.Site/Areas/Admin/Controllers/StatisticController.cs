namespace SabaNews.Site.Areas.Admin.Controllers
{
    using System;
    using System.Threading.Tasks;
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Models.Posts;
    using SabaNews.Models.ViewModel.Statistic;
    using SabaNews.Services.Interfaces;

    public class StatisticController : AdminController
    {
        private readonly IStatisticService statisticService;

        public StatisticController(IStatisticService statisticService)
        {
            this.statisticService = statisticService;
        }

        public IActionResult Index()
        {
            DateTime now = DateTime.Now;
            StatisticFilterViewModel viewModel = new()
            {
                FromDate = new DateTime(now.Year, now.Month, 1),
                ToDate = now
            };
            return View(viewModel);
        }

        [HttpPost]
        public async Task<IActionResult> PostViewStatistic(StatisticFilterViewModel viewModel)
        {
            var statistic = await statisticService.GetPostViewStatistic(viewModel.FromDate, viewModel.ToDate);
            var statisticViewModel = new PostViewStatisticModel
            {
                PostViewDetails = statistic
            };
            return PartialView("_PostViewStatistic", statisticViewModel);
        }
    }
}