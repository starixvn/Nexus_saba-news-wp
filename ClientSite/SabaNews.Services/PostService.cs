namespace SabaNews.Services
{
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Text.RegularExpressions;
    using System.Threading.Tasks;
    using Microsoft.Extensions.Configuration;
    using SabaNews.Core.Constants;
    using SabaNews.Criteria;
    using SabaNews.Models;
    using SabaNews.Services.Interfaces;

    public class PostService : IPostService
    {
        private readonly IBaseService baseService;
        private readonly ISerializerService serializerService;
        private readonly IConfiguration configuration;

        public PostService(IBaseService baseService, ISerializerService serializerService, IConfiguration configuration)
        {
            this.baseService = baseService;
            this.serializerService = serializerService;
            this.configuration = configuration;
        }

        public async Task<IEnumerable<Post>> GetPostsBySlugAsync(string slug, int start, int end)
        {
            var criteria = new GetPostsBySlugCriteria
            {
                _CategorySlug = slug.Trim(),
                _Start = start,
                _End = end
            };
            var posts = await baseService.GetDataAsync<Post>(criteria);

            return FormatData(posts).ToList();
        }

        public async Task<PostTemplate> GetPostsByCateSlugAsync(PostParam postParam)
        {
            var criteria = new GetPostsByCateSlugCriteria
            {
                _CategorySlug = postParam.Slug,
                _Start = postParam.Start,
                _End = postParam.End
            };
            var posts = await baseService.GetDataAsync<Post>(criteria);
            var postTemplate = new PostTemplate
            {
                Posts = FormatData(posts).ToList(),
                TotalItems = criteria.TotalItems
            };
            return postTemplate;
        }

        public async Task<IEnumerable<Post>> GetPostsByCateSlugNotContainChidrenAsync(GetPostsByCateSlugNotContainChildrenCriteria criteria)
        {
            var posts = await baseService.GetDataAsync<Post>(criteria);
            return FormatData(posts).ToList();
        }

        public async Task<PostTemplate> GetRecentPostsAsync(PostParam postParam)
        {
            var criteria = new GetRecentPostsCriteria
            {
                Start = postParam.Start,
                End = postParam.End,
                ExceptSlug = postParam.Slug.Trim()
               
            };
            var posts = await baseService.GetDataAsync<Post>(criteria);
            if (postParam.PostID != 0)
            {
                var excerptCurrentPost = posts.Where(item => item.ID != postParam.PostID).ToList();
                var totalRealItems = postParam.End - 1;
                posts = excerptCurrentPost;
                if (excerptCurrentPost.Count > totalRealItems)
                {
                    posts = excerptCurrentPost.Skip(postParam.Start).Take(totalRealItems).ToList();
                }
            }
            var postTemplate = new PostTemplate
            {
                Posts = FormatData(posts).ToList(),
                TotalItems = criteria.TotalItems
            };
            return postTemplate;
        }

        public async Task<IEnumerable<Post>> GetTopPostsByCateSlugAsync(PostParam postParam)
        {
            var criteria = new GetTopPostsByCateSlugCriteria
            {
                CateSlug = postParam.Slug.Trim(),
                Start = postParam.Start,
                End = postParam.End
            };
            var posts = await baseService.GetDataAsync<Post>(criteria);

            if (postParam.PostID != 0)
            {
                var totalRealItems = postParam.End - 1;
                var excerptCurrentPost = posts.Where(item => item.ID != postParam.PostID).ToList();
                posts = excerptCurrentPost;
                if (excerptCurrentPost.Count > totalRealItems)
                {
                    posts = excerptCurrentPost.Skip(postParam.Start).Take(totalRealItems).ToList();
                }
            }

            return FormatData(posts).ToList();
        }

        public async Task<Post> GetPostDetailBySlugAsync(string slug, string postType)
        {
            var criteria = new GetPostDetailBySlugCriteria
            {
                Slug = slug.Trim(),
                PostType = postType
            };
            var post = await baseService.GetDataEntityAsync<Post>(criteria);
            if (post != null)
            {
                var excerpt = Regex.Replace(post.PostContent, @"<[^>]+>|&nbsp;", "").Trim();
                var subTitle = excerpt.IndexOf("\n", StringComparison.OrdinalIgnoreCase);
                post.PostExcerpt = subTitle != -1 ? excerpt.Substring(0, subTitle) : excerpt;
                post.Images = serializerService.GetListImageFromMetadata(post.FeatureImage, post.Metadata);
                post.CategoriesSlug ??= "";
                post.CatesName ??= "";

                if (postType == Constants.pageType)
                {
                    return post;
                }
                var isVideo = await CheckTypeCategoryAsync(Constants.VideoSlug, post.ID);
                post.Type = isVideo ? Constants.VideoPostType : Constants.FootballPostType;
            }
            return post;
        }

        public async Task<PostTemplate> SearchPostAsync(string keyword, int start, int end)
        {
            var criteria = new SearchPostsCriteria
            {
                _Keyword = keyword,
                _Start = start,
                _End = end
            };
            var posts = await baseService.GetDataAsync<Post>(criteria);
            var postTemplate = new PostTemplate
            {
                Posts = FormatData(posts).ToArray(),
                TotalItems = criteria.TotalItems
            };
            return postTemplate;
        }

        public async Task<PostTemplate> GetPostByTagAsync(string tag, int start, int end)
        {
            var criteria = new GetPostsByTagCriteria
            {
                _Tag = tag.Trim(),
                _Start = start,
                _End = end
            };
            var posts = await baseService.GetDataAsync<Post>(criteria);
            var postTemplate = new PostTemplate
            {
                Posts = FormatData(posts).ToArray(),
                TotalItems = criteria.TotalItems
            };
            return postTemplate;
        }

        public async Task<IEnumerable<Post>> GetSavedPostsByUserID(string IDs)
        {
            var criteria = new GetPostsByIDsCriteria
            {
                IDs = IDs
            };
            var posts = await baseService.GetDataAsync<Post>(criteria);

            return FormatData(posts).ToArray();
        }

        public async Task<Category> GetCateDetailAsync(string slug)
        {
            var criteria = new GetCateDetailCriteria
            {
                _Slug = slug
            };
            var cate = await baseService.GetDataEntityAsync<Category>(criteria);
            return ReplaceStringFromVarSeo(cate);
        }

        public async Task<Category> GetPageDetailBySlugAsync(string slug)
        {
            var criteria = new GetPageDetailBySlugCriteria
            {
                Slug = slug
            };
            var cate = await baseService.GetDataEntityAsync<Category>(criteria);
            return ReplaceStringFromVarSeo(cate);
        }

        public async Task<bool> CheckTypeCategoryAsync(string parentSlug, int ID)
        {
            var criteria = new CheckTypeCategoryCriteria
            {
                _Parent = parentSlug,
                _ID = ID
            };
            var cate = await baseService.GetDataEntityAsync<Category>(criteria);
            return cate != null;
        }

        public async Task<IEnumerable<Menu>> GetMenuAsync(string MenuName)
        {
            var criteria = new GetMenuCriteria
            {
                _Menu_name = MenuName
            };
            return await baseService.GetDataAsync<Menu>(criteria);
        }

        private IEnumerable<Post> FormatData(IEnumerable<Post> posts)
        {
            foreach (var post in posts)
            {
                var excerpt = Regex.Replace(post.PostContent, @"<[^>]+>|&nbsp;", "").Trim();
                var subTitle = excerpt.IndexOf("\n", StringComparison.OrdinalIgnoreCase);
                post.PostExcerpt = subTitle != -1 ? excerpt.Substring(0, subTitle) : excerpt;
                post.Images = serializerService.GetListImageFromMetadata(post.FeatureImage, post.Metadata);
            }
            return posts;
        }

        private static Category ReplaceStringFromVarSeo(Category cate)
        {
            if (cate == null)
            {
                return cate;
            }
            if (cate.SEOTitle != null)
            {
                var result = Regex.Replace(cate.SEOTitle, Constants.SepVar, Constants.Separate, RegexOptions.IgnoreCase);
                cate.SEOTitle = Regex.Replace(result, Constants.SiteNameVar, Constants.SiteName, RegexOptions.IgnoreCase);
            }
            if (cate.Description != null)
            {
                var result = Regex.Replace(cate.Description, Constants.SepVar, Constants.Separate, RegexOptions.IgnoreCase);
                cate.Description = Regex.Replace(result, Constants.SiteNameVar, Constants.SiteName, RegexOptions.IgnoreCase);
            }
            if (cate.OgDescription != null)
            {
                var result = Regex.Replace(cate.OgDescription, Constants.SepVar, Constants.Separate, RegexOptions.IgnoreCase);
                cate.OgDescription = Regex.Replace(result, Constants.SiteNameVar, Constants.SiteName, RegexOptions.IgnoreCase);
            }
            if (cate.OgTitle != null)
            {
                var result = Regex.Replace(cate.OgTitle, Constants.SepVar, Constants.Separate, RegexOptions.IgnoreCase);
                cate.OgTitle = Regex.Replace(result, Constants.SiteNameVar, Constants.SiteName, RegexOptions.IgnoreCase);
            }

            return cate;
        }

        public async Task<IEnumerable<SitemapModel>> GetSitemapDataAsync()
        {
            var domainName = configuration.GetValue<string>("SitemapSettings:Domain");
            var sitemap = await baseService.GetDataAsync<SitemapModel>(new GetSitemapDataCriteria());

            foreach (var item in sitemap)
            {
                if (item.Slug == Constants.WarningSlug)
                {
                    continue;
                }
                item.Url = item.PostType == "category" ? string.Format("{0}tin-the-thao/{1}", domainName, item.Slug) : string.Format("{0}{1}", domainName, item.Slug);
            }

            return sitemap;
        }

        public IList<string> GetBlackListIPs()
        {
            var blackListIPs = configuration.GetValue<string>("BlackListIPs");
            var blackList = new List<string>();
            if (!string.IsNullOrWhiteSpace(blackListIPs))
            {
                var blackListArr = blackListIPs.Split(",");
                if (blackListArr != null && blackListArr.Any())
                {
                    blackList = blackListArr.Select(t => t.Trim()).ToList();
                }
            }

            return blackList;
        }

        public async Task<IEnumerable<NewsSitemapModel>> GetNewsSitemapDataAsync()
        {
            return await baseService.GetDataAsync<NewsSitemapModel>(new GetNewsSitemapCriteria());
        }

        public async Task<IEnumerable<Post>> GetRSSDataAsync()
        {
            var posts = await baseService.GetDataAsync<Post>(new GetRSSCriteria());
            return FormatData(posts);
        }
    }
}