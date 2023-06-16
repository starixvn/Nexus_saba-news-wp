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
        private const string articleDaySlug = "article-day-";
        private const string articleYearSlug = "article-year-";
        private const string xmlSuffix = ".xml";
        private const string xmlType = "text/xml";
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
        public ContentResult Sitemap()
        {
            string sitemap = SitemapHelper.GetSitemapForDayAndYear(domain);
            return Content(sitemap, xmlType, Encoding.UTF8);
        }

        [Route("sitemap/{slug}")]
        public async Task<ContentResult> SitemapGeneral(string slug)
        {
            var sitemap = "";
            if(slug.Contains(articleDaySlug))
            {
                var dateSlug = slug.Replace(articleDaySlug, "").Replace(xmlSuffix, "");
                if(DateTime.TryParse(dateSlug, out DateTime date))
                {
                    var posts = await postService.GetSitemapPostsByDateAsync(date);
                    sitemap = SitemapHelper.GetSitemapDocument(posts, domain);
                }
            }
            else if(slug.Contains(articleYearSlug))
            {
                var yearSlug = slug.Replace(articleYearSlug, "").Replace(xmlSuffix, "");
                if(Int32.TryParse(yearSlug, out int year))
                {
                    sitemap = SitemapHelper.GetSitemapForYear(year, domain);

                }
            }
            else {
                sitemap = "";
            }
            return Content(sitemap, xmlType, Encoding.UTF8);
        }

        [Route("sitemap/categories.xml")]
        public async Task<ContentResult> SitemapCategories()
        {
            var menu = await dataControlService.GetMenuData();
            var sitemap = SitemapHelper.GetSitemapCategories(menu, domain);
            return Content(sitemap, xmlType, Encoding.UTF8);
        }

        [Route("sitemap/tag.xml")]
        public async Task<ContentResult> SitemapTags()
        {
            var tags = await postService.GetAllTagAsync();
            var sitemap = SitemapHelper.GetSitemapTags(tags, domain);
            return Content(sitemap, xmlType, Encoding.UTF8);
        }

        [Route("sitemap-news.xml")]
        public async Task<ContentResult> SitemapNews()
        {
            var data = await dataControlService.GenerateNewsSitmapXmlData();
            string sitemap = SitemapHelper.GetSitemapNews(data, domain);
            return Content(sitemap, xmlType, Encoding.UTF8);
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
            return Content(rss, xmlType, Encoding.UTF8);
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