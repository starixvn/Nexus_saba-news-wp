using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using SabaNews.Core.Constants;
using SabaNews.Models.ViewModel;
using SabaNews.Services.Interfaces;

namespace SabaNews.Site.Controllers.Event
{
    public class Euro2024Controller : Controller
    {
        private readonly IDataControlService dataControlService;
        public Euro2024Controller(IDataControlService dataControlService)
        {
            this.dataControlService = dataControlService;
        }
        [Route("/euro-2024")]
        public async Task<IActionResult> Index ()
        {
            var model = await dataControlService.GetWorldCupPageViewModel();
            var header = new HeaderViewModel
            {
                HeadTitle = model.Category.Title,
                Title = Euro2024.TitleHomepage,
                Description = model.Category.Description,
                Slug = Euro2024.HomepageSlug
            };
            model.Header = header;
            return View("~/Views/Event/Euro2024/Index.cshtml", model);
        }
        [Route("/euro-2024/{slug}")]
        public async Task<IActionResult> PostDetailEuro (string slug)
        {
            return View("");
        }
    }
}