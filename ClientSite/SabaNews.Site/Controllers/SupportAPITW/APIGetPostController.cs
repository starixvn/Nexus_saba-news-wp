namespace SabaNews.Site.Controllers.SupportAPITW
{
    using Fanex.Caching;
    using Microsoft.AspNetCore.Mvc;
    using Microsoft.Extensions.Options;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Models.Posts;
    using SabaNews.Models.ViewModel.Posts;
    using SabaNews.Services;
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;

    public class APIGetPostController : Controller
    {
        private readonly LivescoreMainPageCacheConfig cacheConfigs;
        private readonly IPostService postService;
        private readonly ICacheWrapperService cacheService;

        public APIGetPostController(IPostService postService,
            IOptionsSnapshot<LivescoreMainPageCacheConfig> cacheConfigs,
            ICacheWrapperService cacheService)
        {
            this.cacheConfigs = cacheConfigs.Value;
            this.postService = postService;
            this.cacheConfigs.OutOfRangeTimeout = 1;
            this.cacheService = cacheService;
        }

        [HttpGet]
        [Route("api/post/getlist")]
        public async Task<IActionResult> GetPostList(GetDataViewModel getDataView)
        {
            var token = Request.Headers["Token"];
            IList<GetData> posts = new List<GetData>();

            if (GetDataViewModel.IsValid(token))
            {
                if (cacheConfigs.Enabled)
                {
                    var cacheKey = string.Format("{0}{1}{2}{3}", CacheKey.GetPostList, getDataView.LastTimestamp, getDataView.Category, getDataView.Subject);
                    posts = await cacheService.GetAsync<IList<GetData>>(cacheKey);
                    if (posts is null)
                    {
                        posts = await postService.GetPostlist(getDataView);

                        await cacheService.SetAsync(cacheKey, posts,
                            new CacheItemOptions
                            {
                                RelativeExpiration = TimeSpan.FromMinutes(1)
                            });
                    }
                }
                else
                {
                    posts = await postService.GetPostlist(getDataView);
                }
            }
            return Json(posts);
        }

        [HttpGet]
        [Route("api/post/getdetail")]
        public async Task<IActionResult> GetDetailContent(GetDataViewModel getDataView)
        {
            var token = Request.Headers["Token"];
            var posts = new GetDetailContent();
            if (GetDataViewModel.IsValid(token))
            {
                if (cacheConfigs.Enabled)
                {
                    var cacheKey = string.Format("{0}{1}", CacheKey.GetPostDetailContent, getDataView.PostId);
                    posts = await cacheService.GetAsync<GetDetailContent>(cacheKey);
                    if (posts is null)
                    {
                        posts = await postService.GetPostDetailContent(getDataView);

                        await cacheService.SetAsync(cacheKey, posts,
                            new CacheItemOptions
                            {
                                RelativeExpiration = TimeSpan.FromMinutes(1)
                            });
                    }
                }
                else
                {
                    posts = await postService.GetPostDetailContent(getDataView);
                }
            }
            return Json(posts);
        }
    }
}
