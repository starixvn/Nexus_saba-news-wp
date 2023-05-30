namespace Sabanews.Controllers
{
    using System;
    using System.Collections.Generic;
    using System.Text;
    using System.Threading.Tasks;
    using Fanex.Caching;
    using Microsoft.AspNetCore.Mvc;
    using Microsoft.Extensions.Configuration;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Models;
    using SabaNews.Models.Posts;
    using SabaNews.Models.RSS;
    using SabaNews.Services;
    using SabaNews.Services.Interfaces;
    using SabaNews.Site.Extensions;

    public class RobotsController : Controller
    {
        private readonly IDataControlService dataControlService;
        private readonly IPostService postService;
        private readonly ICacheWrapperService cacheService;
        private readonly IConfiguration configuration;

        private const string RssCacheKey = "RSSCacheConfig";
        private readonly string domain;

        public RobotsController(
            IDataControlService dataControlService,
            IConfiguration configuration,
            IPostService postService,
            ICacheWrapperService cacheService)
        {
            this.dataControlService = dataControlService;
            this.postService = postService;
            this.configuration = configuration;
            this.cacheService = cacheService;
            domain = configuration.GetValue<string>("BaseUrl");
        }

        [Route("/robots.txt")]
        public ContentResult RobotsTxt()
        {
            var sb = new StringBuilder();
            sb.AppendLine("User-agent: *")
                .AppendLine("allow: /")
                .Append("Sitemap: ")
                .Append(domain)
                .AppendLine("sitemap.xml")
                .Append("Sitemap: ")
                .Append(domain)
                .AppendLine("sitemap-news.xml");

            return Content(sb.ToString(), "text/plain", Encoding.UTF8);
        }

        [Route("sitemap.xml")]
        public async Task<ContentResult> Sitemap()
        {
            var data = await dataControlService.GenerateSitmapXmlData();
            string sitemap = SitemapHelper.GetSitemapDocument(data, domain);
            return Content(sitemap, "text/xml", Encoding.UTF8);
        }

        [Route("sitemap-news.xml")]
        public async Task<ContentResult> SitemapNews()
        {
            var data = await dataControlService.GenerateNewsSitmapXmlData();
            string sitemap = SitemapHelper.GetSitemapNews(data, domain);
            return Content(sitemap, "text/xml", Encoding.UTF8);
        }

        [Route("rss.html")]
        public async Task<IActionResult> RSS()
        {
            var menu = await dataControlService.GetMenuData();
            var model = new RSSHomeModel
            {
                Menus = menu,
                BaseUrl = domain
            };
            return View(model);
        }

        [Route("rss/{cate}")]
        public async Task<ContentResult> CategoryRSS(string cate)
        {
            var slug = cate.Replace(".rss", "");
            RSSCategoryModel model;
            var cacheConfig = configuration.GetSection(RssCacheKey).Get<RssCacheConfig>();
            if(cacheConfig.Enabled)
            {
                var cacheKey = $"rss-{slug}";
                model = await cacheService.GetAsync<RSSCategoryModel>(cacheKey);
                if(model is null)
                {
                    model = await GetDataCategory(slug);
                    await cacheService.SetAsync(cacheKey, model,
                    new CacheItemOptions
                    {
                        RelativeExpiration = TimeSpan.FromMinutes(cacheConfig.Timeout)
                    });
                }
            }
            else
            {
                model = await GetDataCategory(slug);
            }
            string rss = RssHelper.GetRSSDocument(model, domain);
            return Content(rss, "text/xml", Encoding.UTF8);
        }

        private async Task<RSSCategoryModel> GetDataCategory(string slug)
        {
            IEnumerable<Post> posts;
            Category category;
            if(slug == Constants.HomepageRSS)
            {
                posts = await postService.GetRSSRecentBySlugAsync();
                category = await postService.GetPageDetailBySlugAsync(Constants.HomeSlug);
            }
            else
            {
                posts = await postService.GetRSSCategoryBySlugAsync(slug);
                category = await postService.GetCateDetailAsync(slug);
                category.Url  = slug + CategoryKey.Suffix;
            }
            var model = new RSSCategoryModel
            {
                Posts = posts,
                Category = category
            };
            return model;
        }
    }
}