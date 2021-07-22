namespace SabaNews.Site.Controllers.Admin
{
    using System;
    using System.Threading.Tasks;
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Models.ViewModel.Statistic;
    using SabaNews.Services.Interfaces;

    public class StatisticController : Controller
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
                ToDate = new DateTime(now.Year, now.Month, now.Day)
            };
            return View("~/Admin/Statistic/Index.cshtml", viewModel);
        }

        [HttpPost]
        public async Task<IActionResult> PostViewStatistic([FromBody] StatisticFilterViewModel viewModel)
        {
            var statistic = await statisticService.GetPostViewStatistic(viewModel.FromDate, viewModel.ToDate);
            return Json(statistic);
        }
    }
}