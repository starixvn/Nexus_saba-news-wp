namespace SabaNews.Services
{
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Text.RegularExpressions;
    using System.Threading.Tasks;
    using Microsoft.Extensions.Configuration;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Helper;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Criteria;
    using SabaNews.Criteria.Category;
    using SabaNews.Criteria.Posts;
    using SabaNews.Models;
    using SabaNews.Models.Categories;
    using SabaNews.Models.Posts;
    using SabaNews.Models.ViewModel.Posts;
    using SabaNews.Resources;
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

        public async Task<IEnumerable<Post>> GetPostsByCateSlugAsync(GetPostsByCateSlugCriteria criteria)
        {
            var posts = await baseService.GetDataAsync<Post>(criteria);
            return FormatData(posts).ToList();
        }

        public async Task<IEnumerable<Post>> GetPostsByCateSlugNotContainChidrenAsync(GetPostsByCateSlugNotContainChildrenCriteria criteria)
        {
            var posts = await baseService.GetDataAsync<Post>(criteria);
            return FormatData(posts).ToList();
        }

        public async Task<IEnumerable<Post>> GetRecentPostsAsync(GetRecentPostsParam postParam)
        {
            var criteria = new GetRecentPostsCriteria
            {
                Start = postParam.Start,
                End = postParam.End
            };
            var posts = await baseService.GetDataAsync<Post>(criteria);
            postParam.TotalIItems = criteria.TotalItems;
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
            return FormatData(posts).ToList();
        }

        public async Task<IEnumerable<Post>> GetTopPostsByCateSlugAsync(PostParam postParam)
        {
            var criteria = new GetTopPostsByCateSlugCriteria
            {
                CateSlug = postParam.Slug.Trim(),
                Start = postParam.Start,
                End = postParam.End,
                PostId = postParam.PostID
            };
            var posts = await baseService.GetDataAsync<Post>(criteria);

            return FormatData(posts).ToList();
        }

        public async Task<Post> GetPostDetailBySlugAsync(string slug, string postType)
        {
            var criteria = new GetPostDetailBySlugCriteria
            {
                Slug = slug,
                PostType = postType
            };
            var post = await baseService.GetDataEntityAsync<Post>(criteria);
            if (post != null)
            {
                if (string.IsNullOrEmpty(post.PostExcerpt))
                {
                    var excerpt = Regex.Replace(post.PostContent, @"<[^>]+>|&nbsp;", "").Trim();
                    var subTitle = excerpt.IndexOf("\n", StringComparison.OrdinalIgnoreCase);
                    post.PostExcerpt = subTitle != -1 ? excerpt.Substring(0, subTitle) : excerpt;
                }

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

        public async Task<IEnumerable<Post>> SearchPostAsync(SearchPostsCriteria criteria)
        {
            if (string.IsNullOrEmpty(criteria._Keyword))
            {
                return null;
            }
            var posts = await baseService.GetDataAsync<Post>(criteria);

            return FormatData(posts).ToArray();
        }

        public async Task<IEnumerable<Post>> GetPostByTagAsync(GetPostsByTagCriteria criteria)
        {
            var posts = await baseService.GetDataAsync<Post>(criteria);
            return FormatData(posts).ToArray();
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
                Slug = slug
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

        public async Task<IEnumerable<Post>> GetPostsByCateSlugs(GetPostsByCateSlugsCriteria criteria)
        {
            var result = await baseService.GetDataAsync<Post>(criteria);
            return FormatData(result).ToList();
        }

        public async Task<IEnumerable<Post>> GetPostsByCateSlugsPaging(GetPostsByCateSlugsPagingCriteria criteria)
        {
            var result = await baseService.GetDataAsync<Post>(criteria);
            return FormatData(result).ToList();
        }

        public async Task<IList<CategoryGeneral>> GetCateParentsAsync(string slug)
        {
            var result = await baseService.GetDataAsync<CategoryGeneral>(new GetCateParentsCriteria { Slug = slug });
            var cates = result.Reverse();
            return cates.ToList();
        }

        public async Task<IList<GetData>> GetPostlist(GetDataViewModel getData)
        {
            DateTime dateTime = new(1970, 1, 1, 0, 0, 0, 0, DateTimeKind.Utc);
            dateTime = dateTime.AddSeconds(getData.LastTimestamp).ToLocalTime();
            var criteria = new GetPostlistCriteria { _ToDate = dateTime, _Category = getData.Category, _Subject = getData.Subject };
            var result = await baseService.GetDataAsync<GetData>(criteria);
            result.ToList().ForEach(x => x.FormatDate());
            return result.ToList();
        }

        public async Task<GetDetailContent> GetPostDetailContent(GetDataViewModel getData)
        {
            var post = await baseService.GetDataAsync<Post>(new GetPostDetailContentCriteria { _postId = getData.PostId });
            var result = new GetDetailContent
            {
                PostId = post.First().ID,
                Title = post.First().PostTitle,
                PublishDate = post.First().PostDateGMT.AddHours(8).ToString("yyyy-MM-ddTHH:mm:ss.fffZ"),
                Subjects = post.First().CategoriesSlug != null ? HandleSubjects(post.First().CategoriesSlug) : null,
                Content = CheckVideo(post.First().CategoriesSlug) ? null : post.First().PostContent,
                SabaVideoContent = CheckVideo(post.First().CategoriesSlug) ? post.First().PostContent : null,
                EditorId = post.First().EditorId,
                Tags = post.First().Tags != null ? HandleTags(post.First().Tags) : null,
                Category = CheckVideo(post.First().CategoriesSlug) ? Constants.GetFourItems : Constants.GetThreeItems,
                UrlSlug = configuration.GetValue<string>("BaseUrl") + post.First().PostName
            };
            return result;
        }

        private static IList<Subjects> HandleSubjects(string categoriesSlug)
        {
            var subjects = new List<Subjects>();
            string[] categories = categoriesSlug.Split(new []{ "," }, StringSplitOptions.None);
            foreach(var item in categories)
            {
                if(item == Constants.BasketballSlug || 
                    item == Constants.BasketballVideoSlug)
                {
                    subjects.Add(new Subjects { Id = Constants.IdBasketballEN, Name = ViewResources.BasketballEN });
                }
                if (item == Constants.TennisSlug ||
                    item == Constants.TennisVideoSlug)
                {
                    subjects.Add(new Subjects { Id = Constants.IdTennisEN, Name = ViewResources.TennisEN });
                }
                if (CheckSoccer(item))
                {
                    subjects.Add(new Subjects { Id = Constants.IdSoccerEN, Name = ViewResources.SoccerEN });
                }
            }
            if(subjects.Count > 1)
            {
                subjects = new List<Subjects> { new Subjects { Id = Constants.IdAll, Name = ViewResources.All} };
            }
            return subjects;
        }

        private static bool CheckSoccer(string category)
        {
            const string search = Constants.FootballSlug + Constants.VideoFootballSlug +
                                Constants.Euro2020VideoSlug + Constants.VideoWorldCup2022Slug +
                                Constants.VideoGuessWorldCup2022Slug + Constants.VideoHighlightWorldCup2022Slug;
            if (search.Contains(category))
            {
                return true;
            }
            return false;
        }

        private static bool CheckVideo(string categoriesSlug)
        {
            string[] categories = categoriesSlug.Split(new []{ "," }, StringSplitOptions.None);
            foreach (var item in categories)
            {
                if (item.Contains(Constants.VideoSlug))
                {
                    return true;
                }
            }           
            return false;
        }

        private static IList<string> HandleTags(string tags)
        {
            var result = new List<string>();
            string[] tagsList = tags.Split(new []{ ", " }, StringSplitOptions.None);
            tagsList.ToList().ForEach(x => result.Add(x));
            return result;
        }

        private IEnumerable<Post> FormatData(IEnumerable<Post> posts)
        {
            foreach (var post in posts)
            {
                if (string.IsNullOrEmpty(post.PostExcerpt))
                {
                    var excerpt = Regex.Replace(post.PostContent, @"<[^>]+>|&nbsp;", "").Trim();
                    var subTitle = excerpt.IndexOf("\n", StringComparison.OrdinalIgnoreCase);
                    post.PostExcerpt = subTitle != -1 ? excerpt.Substring(0, subTitle) : excerpt;
                }
                post.Images = serializerService.GetListImageFromMetadata(post.FeatureImage, post.Metadata);
            }
            return posts;
        }

        private static Category ReplaceStringFromVarSeo(Category cate)
        {
            const string dateFormat = "dd/MM/yyyy";
            if (cate == null)
            {
                return cate;
            }
            if (cate.SEOTitle != null)
            {
                var result = Regex.Replace(cate.SEOTitle, Constants.SepVar, Constants.Separate, RegexOptions.IgnoreCase);
                result =  Regex.Replace(result, Constants.SiteNameVar, Constants.SiteName, RegexOptions.IgnoreCase);
                cate.SEOTitle = Regex.Replace(result, Constants.CurrentDateVar, DateTimeUtilize.FormatDateTime(DateTime.UtcNow, dateFormat), RegexOptions.IgnoreCase);
            }
            if (cate.Description != null)
            {
                var result = Regex.Replace(cate.Description, Constants.SepVar, Constants.Separate, RegexOptions.IgnoreCase);
                result = Regex.Replace(result, Constants.SiteNameVar, Constants.SiteName, RegexOptions.IgnoreCase);
                cate.Description = Regex.Replace(result, Constants.CurrentDateVar, DateTimeUtilize.FormatDateTime(DateTime.UtcNow, dateFormat), RegexOptions.IgnoreCase);
            }
            if (cate.OgDescription != null)
            {
                var result = Regex.Replace(cate.OgDescription, Constants.SepVar, Constants.Separate, RegexOptions.IgnoreCase);
                result = Regex.Replace(result, Constants.SiteNameVar, Constants.SiteName, RegexOptions.IgnoreCase);
                cate.OgDescription = Regex.Replace(result, Constants.CurrentDateVar, DateTimeUtilize.FormatDateTime(DateTime.UtcNow, dateFormat), RegexOptions.IgnoreCase);
            }
            if (cate.OgTitle != null)
            {
                var result = Regex.Replace(cate.OgTitle, Constants.SepVar, Constants.Separate, RegexOptions.IgnoreCase);
                result = Regex.Replace(result, Constants.SiteNameVar, Constants.SiteName, RegexOptions.IgnoreCase);
                cate.OgTitle = Regex.Replace(result, Constants.CurrentDateVar, DateTimeUtilize.FormatDateTime(DateTime.UtcNow, dateFormat), RegexOptions.IgnoreCase);
            }

            return cate;
        }

        public async Task<IEnumerable<SitemapModel>> GetSitemapDataAsync()
        {
            var sitemap = await baseService.GetDataAsync<SitemapModel>(new GetSitemapDataCriteria());
            var posts = sitemap.Where(item => item.PostType != "category").ToList();
            var categories = sitemap.Where(item => item.PostType == "category").ToList();

            foreach (var item in posts)
            {
                item.Url = item.Slug;
            }
            posts.AddRange(SiteMap.Pages.Select(item => new SitemapModel { Url = item }));
            var cates = GetCategoriesSiteMap(categories);

            var newSiteMap = posts.Concat(cates);

            return newSiteMap;
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

        public async Task<CommandResult> UpdateVideoView(int postId)
        {
            var command = new UpdateVideoViewCommand
            {
                PostId = postId,
            };

            return await baseService.ExecuteNonQueryAsync(command);
        }

        public async Task<CommandResult> UpdateViewAsync(int postId, bool isVideo)
        {
            var command = new UpdateViewCommand
            {
                PostId = postId,
                IsVideoPost = isVideo
            };

            return await baseService.ExecuteNonQueryAsync(command);
        }

        private List<SitemapModel> GetCategoriesSiteMap(IList<SitemapModel> categories, int cateId = 0, string link = "")
        {
            var cateList = new List<SitemapModel>();
            foreach (var cate in categories.Where(item => item.Parent == cateId).ToList())
            {
                var url = link != "" ? $"{link}/{cate.Slug}" : cate.Slug;
                if (cate.Slug == Constants.Euro2020Slug)
                {
                    url = url.Replace($"{Constants.NewPostSlug}/", "");
                }
                cate.Url = url;
                cateList.Add(cate);
                var subCate = categories.Where(item => item.Parent == cate.ID).ToList();
                if (subCate.Any())
                {
                    var tmp = GetCategoriesSiteMap(categories, cate.ID, url);
                    cateList.AddRange(tmp);
                }
            }
            return cateList.ToList();
        }
    }
}