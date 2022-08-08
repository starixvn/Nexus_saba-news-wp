namespace SabaNews.Site.Controllers.Event
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Services;
    using SabaNews.Services.Interfaces;


    public class WorldCup2022Controller : Controller
    {
        private readonly IDataControlService dataControlService;
        private readonly IPostService postService;

        public WorldCup2022Controller(
            IPostService postService,
            IDataControlService dataControlService)
        {
            this.postService = postService;
            this.dataControlService = dataControlService;
        }
        [Route("tin-the-thao/worldcup-2022")]
        public IActionResult Index()
        {
            return View("~/Views/Event/WorldCup2022/Index.cshtml");
        }
    }
}
