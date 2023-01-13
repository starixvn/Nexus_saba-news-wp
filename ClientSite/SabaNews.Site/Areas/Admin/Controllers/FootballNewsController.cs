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
        public async Task<ActionResult> Index()
        {
            var footballnews1 = await optionService.GetOptionAsync(OptionKey.footballnews1);
            var footballnews2 = await optionService.GetOptionAsync(OptionKey.footballnews2);
            var footballnews3 = await optionService.GetOptionAsync(OptionKey.footballnews3);

            List<IEnumerable<Post>> leaguePosts = new()
            {
                await postService.GetPostsBySlugAsync(footballnews1.OptionValue, Constants.FromFirstItem, Constants.GetFourItems),
                await postService.GetPostsBySlugAsync(footballnews2.OptionValue, Constants.FromFirstItem, Constants.GetFourItems),
                await postService.GetPostsBySlugAsync(footballnews3.OptionValue, Constants.FromFirstItem, Constants.GetFourItems)
            };

            var menuName = configuration.GetValue<string>("MenuName");
            var menus = await postService.GetMenuAsync(menuName);
            var urls = new List<CateInfo>();
            foreach (var item in menus)
            {
                var cateInfo = GetCateInfo(item.MenuTitle, item.Slug);
                var cateParents = await postService.GetCateParentsAsync(cateInfo.Slug);
                cateInfo.Url ??= PostHelper.GetCategoryLink(cateParents);
                urls.Add(cateInfo);
            }

            return View(); 
        }

        public async Task<ActionResult> GetSlugs()
        {
            var menuName = configuration.GetValue<string>("MenuName");
            var menus = await postService.GetMenuAsync(menuName);
            return Json(menus);
        }

        public async Task<ActionResult> GetUrlSlug(string slug)
        {
            var post = await postService.GetPostDetailBySlugAsync(slug, Constants.postType);
            if (post == null)
            {
                return Json(null);
            }
            var cateInfo = GetCateInfo(post.CatesName, post.CategoriesSlug);
            var cateParents = await postService.GetCateParentsAsync(cateInfo.Slug);
            cateInfo.Url ??= PostHelper.GetCategoryLink(cateParents);
            return Json(cateInfo.Url);
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
    }
}
