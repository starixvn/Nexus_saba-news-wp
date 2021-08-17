namespace SabaNews.Site.Areas.Admin.Controllers
{
    using System;
    using System.Linq;
    using System.Threading.Tasks;
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Models.Posts;
    using SabaNews.Models.ViewModel.Statistic;
    using SabaNews.Resources;
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

        public IActionResult ViewDetails(DateTime? createdDate, bool isVideoDetails)
        {
            if (!createdDate.HasValue)
            {
                return RedirectToAction("Index", "Statistic");
            }

            ViewBag.CreatedDate = createdDate;
            ViewBag.IsVideo = isVideoDetails;
            ViewBag.Title = isVideoDetails ? ViewResources.VideoDetails : ViewResources.ArticleDetails;

            return View();
        }

        [HttpPost]
        public async Task<IActionResult> GetViewDetails(DateTime? createdDate, bool isVideo = false, string viewSort = null)
        {
            var viewDetails = await statisticService.GetPostViewDetails(createdDate.Value, isVideo);

            if (!string.IsNullOrWhiteSpace(viewSort))
            {
                viewDetails = viewSort == "desc" ? viewDetails.OrderByDescending(x => x.TotalViews) :
                    viewDetails.OrderBy(x => x.TotalViews);
            }

            var viewModel = new ViewDetailsViewModel
            {
                ViewDetails = viewDetails,
                CreatedDate = createdDate.Value,
                Type = isVideo ? ViewResources.Videos : ViewResources.Articles,
                Quantity = viewDetails.Count(),
                ViewSort = viewSort,
                TotalView = viewDetails.Sum(x => x.TotalViews)
            };

            return PartialView("_ViewDetailsGrid", viewModel);
        }
    }
}