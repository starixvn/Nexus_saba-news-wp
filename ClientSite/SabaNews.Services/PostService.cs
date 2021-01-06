namespace SabaNews.Services
{
    using SabaNews.Core.Constants;
    using SabaNews.Criteria;
    using SabaNews.Services.Interfaces;
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Text.RegularExpressions;
    using System.Threading.Tasks;
    using SabaNews.Models;

    public class PostService : IPostService
    {
        private readonly IBaseService baseService;

        public PostService(IBaseService baseService)
        {
            this.baseService = baseService;
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

            return FormatData(posts, slug).ToList();
        }

        public async Task<PostTemplate> GetPostsByCateSlugAsync(string cateSlug, int start, int end)
        {
            var criteria = new GetPostsByCateSlugCriteria
            {
                _CategorySlug = cateSlug.Trim(),
                _Start = start,
                _End = end
            };
            var posts = await baseService.GetDataAsync<Post>(criteria);
            var postTemplate = new PostTemplate
            {
                Posts = FormatData(posts).ToList(),
                TotalItems = criteria.TotalItems
            };
            return postTemplate;
        }

        public async Task<PostTemplate> GetRecentPostsAsync(string exceptCategorySlug, int start, int end)
        {
            var criteria = new GetRecentPostsCriteria
            {
                ExceptSlug = exceptCategorySlug.Trim(),
                Start = start,
                End = end
            };
            var posts = await baseService.GetDataAsync<Post>(criteria);
            var postTemplate = new PostTemplate
            {
                Posts = FormatData(posts, Constants.None).ToList(),
                TotalItems = criteria.TotalItems
            };
            return postTemplate;
        }

        public async Task<Post> GetPostDetailBySlugAsync(string slug)
        {
            var criteria = new GetPostDetailBySlugCriteria
            {
                _Slug = slug.Trim()
            };
            var post = await baseService.GetDataEntityAsync<Post>(criteria);
            if (post != null)
            {
                var excerpt = Regex.Replace(post.PostContent, @"<[^>]+>|&nbsp;", "").Trim();
                post.PostExcerpt = (excerpt.Length > Constants.ExcerptLength) ? excerpt.Substring(0, Constants.ExcerptLength) : excerpt;
                if (post.Categories == null)
                {
                    return post;
                }
                post.Type = Constants.FootballPostType;
                var isVideo = await CheckTypeCategoryAsync(Constants.VideoSlug, post.ID);
                if (isVideo)
                {
                    post.Type = Constants.VideoPostType;
                }
                else if (post.CategoriesSlug.Contains(Constants.FootballSlug))
                {
                    post.Type = Constants.FootballPostType;
                }
                else if (post.CategoriesSlug.Contains(Constants.BasketballSlug))
                {
                    post.Type = Constants.BasketballPostType;
                }
                else if (post.CategoriesSlug.Contains(Constants.TennisSlug))
                {
                    post.Type = Constants.TennisPostType;
                }
                else
                {
                    post.Type = Constants.FootballPostType;
                }
            }
            return post;
        }

        public async Task<IEnumerable<Post>> GetRelatedPostAsync(int ID)
        {
            var criteria = new GetRelatedPostCriteria
            {
                _ID = ID,
                _Start = 0,
                _End = 4
            };
            return await baseService.GetDataAsync<Post>(criteria);
        }

        public async Task<IEnumerable<Post>> GetRandomReplatedPostAsync(string categories, int ID)
        {
            var criteria = new GetRandomPostCategoriesCriteria
            {
                _Categories = categories,
                _ID = ID
            };
            var posts = await baseService.GetDataAsync<Post>(criteria);

            return FormatData(posts);
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
                Posts = FormatData(posts),
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
                Posts = FormatData(posts),
                TotalItems = criteria.TotalItems
            };
            return postTemplate;
        }

        public async Task<PostTemplate> GetPostByDateAsync(string date, int start, int end)
        {
            var criteria = new GetPostsByDateCriteria
            {
                _Date = date.Trim(),
                _Start = start,
                _End = end
            };
            var posts = await baseService.GetDataAsync<Post>(criteria);
            var postTemplate = new PostTemplate
            {
                Posts = FormatData(posts),
                TotalItems = criteria.TotalItems
            };
            return postTemplate;
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

        public static IEnumerable<Post> FormatData(IEnumerable<Post> posts, string slug = null)
        {
            var isAddThumbnail = true;
            var listAddThumbnail = new List<string> { Constants.NewPostSlug, Constants.VideoSlug, Constants.BackstageSlug, Constants.TennisSlug };
            var checkSlug = listAddThumbnail.FirstOrDefault(item => item.Equals(slug));
            if (checkSlug == null && slug != null)
            {
                isAddThumbnail = false;
            }
            var i = 0;
            foreach (var post in posts)
            {
                var excerpt = Regex.Replace(post.PostContent, @"<[^>]+>|&nbsp;", "").Trim();
                post.PostExcerpt = (excerpt.Length > Constants.ExcerptLength) ? excerpt.Substring(0, Constants.ExcerptLength) : excerpt;
                if (post.FeatureImage != null)
                {
                    var thumbnail = (i == 0 && isAddThumbnail) ? Constants.ThumbnailSize : "";
                    post.FeatureImage = ResizeImage(post.FeatureImage, post.Metadata, thumbnail);
                }
                i++;
            }
            return posts;
        }

        public static string ResizeImage(string image, string metadata, string thumbnail = "")
        {
            var result = image;
            var size = "";
            var extension = "." + image.Split(".").Last();
            var imgTypes = new List<string> { ".jpg", ".bmp", ".gif", ".png", ".jpeg" };
            var imgSizes = new List<string> { "-406x228", "-260x240", "-600x394" ,"-818x461" };
            var type = imgTypes.FirstOrDefault(item => item.Equals(extension));
            size = imgSizes.FirstOrDefault(item => metadata.Contains(item));
            if (thumbnail != "")
            {
                size = metadata.Contains(thumbnail) ? thumbnail : null;
            }
            
            size ??= "";
            if (type != null)
            {
                var imageLength = image.LastIndexOf(type, StringComparison.CurrentCulture);
                result = image.Substring(0, imageLength) + size + type;
            }
            return result;
        }

        public static Category ReplaceStringFromVarSeo(Category cate)
        {
            if (cate == null)
            {
                return cate;
            }
            if (cate.SEOTitle != null)
            {
                cate.SEOTitle = cate.SEOTitle;
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
    }
}