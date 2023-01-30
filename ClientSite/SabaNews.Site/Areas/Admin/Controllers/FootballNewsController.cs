using Microsoft.AspNetCore.Mvc;
using SabaNews.Models.Event.WorldCup;
using SabaNews.Services;
using System.Collections.Generic;
using System.Threading.Tasks;
using System;
using SabaNews.Models.Posts;
using SabaNews.Site.Extensions;
using SabaNews.Core.Models;
using System.Linq;
using SabaNews.Core.Constants;
using Microsoft.Extensions.Configuration;
using SabaNews.Core.Services;
using SabaNews.Services.Interfaces;
using System.Configuration;
using Newtonsoft.Json;
using SabaNews.Models.Home;
using SabaNews.Livescore.Models.League;
using SabaNews.Livescore.Services;
using SabaNews.Models.ViewModel.User;
using SabaNews.Livescore.Services.Criteria.League;
using SabaNews.Models.ManagementLeague;
using System.Globalization;
using OfficeOpenXml.FormulaParsing.Excel.Functions.Text;

namespace SabaNews.Site.Areas.Admin.Controllers
{
    public class FootballNewsController : AdminController
    {
        private readonly IPostService postService;
        private readonly IOptionService optionService;
        private readonly IConfiguration configuration;

        public FootballNewsController(IPostService postService, IConfiguration configuration, IOptionService optionService)
        {
            this.postService = postService;
            this.configuration = configuration;
            this.optionService = optionService;
        }
        public IActionResult Index()
        {
            return View(); 
        }

        public async Task<ActionResult> GetDataFootBallGrid()
        {
            var optionLeft = await optionService.GetOptionAsync(OptionKey.FootballNewsLeft);
            var optionCenter = await optionService.GetOptionAsync(OptionKey.FootballNewsCenter);
            var optionRight = await optionService.GetOptionAsync(OptionKey.FootballNewsRight);

            var FootballNews = new List<FootballNewsHomePage>{
                JsonConvert.DeserializeObject<FootballNewsHomePage>(optionLeft.OptionValue),
                JsonConvert.DeserializeObject<FootballNewsHomePage>(optionCenter.OptionValue),
                JsonConvert.DeserializeObject<FootballNewsHomePage>(optionRight.OptionValue)
            };

            return PartialView("~/Areas/Admin/Views/FootballNews/_FootballNewsDataGrid.cshtml", FootballNews);
        }

        [HttpGet]
        public async Task<ActionResult> EditCategory(int count)
        {
            var menuName = configuration.GetValue<string>("MenuName");
            var menus = await postService.GetMenuAsync(menuName);

            var optionLeft = await optionService.GetOptionAsync(OptionKey.FootballNewsLeft);
            var optionCenter = await optionService.GetOptionAsync(OptionKey.FootballNewsCenter);
            var optionRight = await optionService.GetOptionAsync(OptionKey.FootballNewsRight);

            var footballNews = new List<FootballNewsHomePage>{
                JsonConvert.DeserializeObject<FootballNewsHomePage>(optionLeft.OptionValue),
                JsonConvert.DeserializeObject<FootballNewsHomePage>(optionCenter.OptionValue),
                JsonConvert.DeserializeObject<FootballNewsHomePage>(optionRight.OptionValue)
            };
            ViewBag.SlugSelected = footballNews[count - 1].Slug;
            ViewBag.Count = count;
            return PartialView("~/Areas/Admin/Views/FootballNews/_EditFootBall.cshtml", menus);
        }

        [HttpPost]
        public async Task<ActionResult> EditCategory(string category, int count)
        {
            var menuName = configuration.GetValue<string>("MenuName");
            var menus = await postService.GetMenuAsync(menuName);
            var slug = menus.Where(x => x.Slug == category).ToList();
            var cateParents = await postService.GetCateParentsAsync(slug.First().Slug);
            var categoryUpdate = new FootballNewsHomePage
            {
                Category = slug.First().MenuTitle,
                Slug = slug.First().Slug,
                CreatedDate = DateTime.UtcNow,
                Url = PostHelper.GetCategoryLink(cateParents)

            };
            categoryUpdate.Url = categoryUpdate.Url[1..];
            var cmdResult = await optionService.UpdateOptionAsync(GetKeyOption(count), JsonConvert.SerializeObject(categoryUpdate));
            return Json(cmdResult);
        }

        public async Task<ActionResult> GetSlugs(string category)
        {
            var menuName = configuration.GetValue<string>("MenuName");
            var menus = await postService.GetMenuAsync(menuName);
            var slug = menus.Where(x => x.Slug == category).ToList();
            return Json(slug[0]);
        }

        private static CateInfo GetCateInfo(string cateName, string cateSlug)
        {
            var slug = cateSlug;
            var name = cateName;
            if (cateSlug == Constants.CollectionSlug || (cateSlug.Contains(Constants.BasketballSlug)
                && !cateSlug.Contains(Constants.BasketballVideoSlug)) || cateSlug.Contains(Constants.TennisSlug))
            {
                return new CateInfo { Slug = Constants.OtherPostSlug, Name = Constants.OtherPosts };
            }

            var listCateSlug = cateSlug.Split(',');
            var listCateName = cateName.Split(',');
            var subCateSlug = listCateSlug.Where(item => item.Trim() != Constants.FootballSlug && item.Trim() != Constants.VideoSlug).ToList();
            var subCateName = listCateName.Where(item => item.Trim() != Constants.Football && item.Trim() != Constants.Video).ToList();
            slug = subCateSlug.Count > 0 ? subCateSlug[0] : listCateSlug[0];
            name = subCateName.Count > 0 ? subCateName[0] : listCateName[0];
            return new CateInfo { Slug = slug.Trim(), Name = name.Trim() };
        }

        private static string GetKeyOption(int count)
        {
            string key;
            if (count == 1) {
                key = OptionKey.FootballNewsLeft;
            }
            else if(count == 2) {
                key = OptionKey.FootballNewsCenter;
            }
            else
            {
                key = OptionKey.FootballNewsRight;
            }
            return key;
        }
    }
}
