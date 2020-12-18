namespace sabanews.Services
{
    using sabanews.Criteria;
    using sabanews.Models;
    using sabanews.Constants;
    using sabanews.Services.Interfaces;
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Text;
    using System.Text.RegularExpressions;
    using System.Threading.Tasks;

    public class PostService : IPostService
    {
        private readonly IBaseService baseService;
        private readonly IPathService pathService;

        public PostService(IBaseService baseService, IPathService pathService)
        {
            this.baseService = baseService;
            this.pathService = pathService;
        }

        public async Task<IEnumerable<Post>> GetPostsAsync(int cateId, int start, int end)
        {
            var criteria = new GetPostsCriteria
            {
                _Category = cateId,
                _Start = start,
                _End = end
            };
            var posts = await baseService.GetDataAsync<Post>(criteria);

            return FormatData(posts);
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

            return FormatData(posts);
        }

        public async Task<Post> GetPostDetailAsync(int ID)
        {
            var criteria = new GetPostDetailCriteria
            {
                ID = ID
            };
            var post = await baseService.GetDataEntityAsync<Post>(criteria);
            if (post != null)
            {
                var excerpt = Regex.Replace(post.PostContent.ToString(), @"<[^>]+>|&nbsp;", "").Trim();
                post.PostExcerpt = (excerpt.Length > 200) ? excerpt.Substring(0, 200) : excerpt;
                post.PostDate = ToRelativeDate(DateTime.Parse(post.PostDate));
            }
            return post;
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
                var excerpt = Regex.Replace(post.PostContent.ToString(), @"<[^>]+>|&nbsp;", "").Trim();
                post.PostExcerpt = (excerpt.Length > 200) ? excerpt.Substring(0, 200) : excerpt;
                post.PostDate = ToRelativeDate(DateTime.Parse(post.PostDate));
                post.Type = Constants.FootballPostType;
                var isVideo = await CheckTypeCategoryAsync(Constants.VideoCategory, post.ID);
                if (isVideo)
                {
                    post.Type = Constants.VideoPostType;
                }
                else if (post.Categories.Contains(Constants.FootballCategory.ToString()))
                {
                    post.Type = Constants.FootballPostType;
                }
                else if (post.Categories.Contains(Constants.BasketballCategory.ToString()))
                {
                    post.Type = Constants.BasketballPostType;
                }
                else if (post.Categories.Contains(Constants.TennisCategory.ToString()))
                {
                    post.Type = Constants.TennisPostType;
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

        public async Task<IEnumerable<Post>> SearchPostAsync(string keyword, int start, int end)
        {
            var criteria = new SearchPostsCriteria
            {
                _Keyword = keyword,
                _Start = start,
                _End = end
            };
            var posts = await baseService.GetDataAsync<Post>(criteria);
            return FormatData(posts);
        }

        public async Task<IEnumerable<Post>> GetPostByTagAsync(string tag, int start, int end)
        {
            var criteria = new GetPostsByTagCriteria
            {
                _Tag = tag.Trim(),
                _Start = start,
                _End = end
            };
            var posts = await baseService.GetDataAsync<Post>(criteria);
            return FormatData(posts);
        }

        public async Task<IEnumerable<Post>> GetPostByDateAsync(string date, int start, int end)
        {
            var criteria = new GetPostsByDateCriteria
            {
                _Date = date.Trim(),
                _Start = start,
                _End = end
            };
            var posts = await baseService.GetDataAsync<Post>(criteria);
            return FormatData(posts);
        }

        public async Task<Category> GetCateDetailAsync(string slug)
        {
            var criteria = new GetCateDetailCriteria
            {
                _Slug = slug
            };
            return await baseService.GetDataEntityAsync<Category>(criteria);
        }
        public async Task<Category> GetPageDetailAsync(int ID)
        {
            var criteria = new GetPageDetailByCateIdCriteria
            {
                ID = ID
            };
            return await baseService.GetDataEntityAsync<Category>(criteria);
        }

        public async Task<bool> CheckTypeCategoryAsync(int parentId, int ID)
        {
            var criteria = new CheckTypeCategoryCriteria
            {
                _Parent = parentId,
                _ID = ID
            };
            var cate = await baseService.GetDataEntityAsync<Category>(criteria);
            return cate != null;
        }

        public async Task<string> GetTagNameBySlugAsync(string slug)
        {
            var criteria = new GetTagNameBySlugCriteria
            {
                _Slug = slug
            };

            var post = await baseService.GetDataEntityAsync<Post>(criteria);
            return post.Slug;
        }

        public async Task<IEnumerable<Menu>> GetMenuAsync(string MenuName)
        {
            var criteria = new GetMenuCriteria
            {
                _Menu_name = MenuName
            };
            return await baseService.GetDataAsync<Menu>(criteria);
        }

        public IEnumerable<Post> FormatData(IEnumerable<Post> posts)
        {
            foreach (var post in posts)
            {
                var excerpt = Regex.Replace(post.PostContent.ToString(), @"<[^>]+>|&nbsp;", "").Trim();
                post.FeatureImage = ResizeImage(post.FeatureImage);
                post.PostExcerpt = (excerpt.Length > 200) ? excerpt.Substring(0, 200) : excerpt;
                post.PostDate = ToRelativeDate(DateTime.Parse(post.PostDate));
            }
            return posts;
        }

        public string GetImageFromFolderInSolution(string image)
        {
            var imageSplit = image.Split("/");
            var imageName = imageSplit[imageSplit.Length - 1];
            var file = pathService.MapPathContentRoot("wordpress/" + imageName);
            if (System.IO.File.Exists(file))
            {
                image = "wordpress/" + imageName;
            }
            return image;
        }

        public string GetImageInFolder(string image)
        {
            var imageSplit = image.Split("/");
            var imageName = imageSplit[imageSplit.Length - 1];
            var file = pathService.MapAbsolutePath(@"C:\Users\hank.nguyen\Desktop\sabaimgs\", imageName);
            if (System.IO.File.Exists(file))
            {
                image = file;
            }
            return image;
        }

        public static string ResizeImage(string image)
        {
            var result = image;
            var extension = "." + image.Split(".").Last();
            var imgTypes = new List<string> { ".jpg", ".bmp", ".gif", ".png", ".jpeg" };
            foreach (var type in imgTypes)
            {
                if (extension == type)
                {
                    var imageLength = image.LastIndexOf(type);

                    result = image.Substring(0, imageLength) + "-600x394" + type;
                    break;
                }
            }
            return result;
        }

        public static string ToRelativeDate(DateTime dateTime)
        {
            var timeSpan = DateTime.Now - dateTime;

            if (timeSpan <= TimeSpan.FromSeconds(60))
                return string.Format("{0} giây trước", timeSpan.Seconds);

            if (timeSpan <= TimeSpan.FromMinutes(60))
                return timeSpan.Minutes > 1 ? String.Format("{0} phút trước", timeSpan.Minutes) : "1 phút trước";

            if (timeSpan <= TimeSpan.FromHours(24))
                return timeSpan.Hours > 1 ? String.Format("{0} giờ trước", timeSpan.Hours) : "1 giờ trước";

            return dateTime.ToString("H:mm dd/MM/yyyy");
        }

        public static string ConvertToUnSign3(string s) // change có dấu thành không dấu
        {
            Regex regex = new Regex("\\p{IsCombiningDiacriticalMarks}+");
            string temp = s.Normalize(NormalizationForm.FormD);
            return regex.Replace(temp, String.Empty).Replace('\u0111', 'd').Replace('\u0110', 'D');
        }

        public static string GenerateSeoFriendlyURL(string Title)
        {
            Title = ConvertToUnSign3(Title);
            Regex regex = new Regex(@"[^a-zA-Z0-9-\s]", (RegexOptions)0);
            Title = regex.Replace(Title, "");
            Title = Title.ToLower().Replace(" - ", "-");
            Title = Title.ToLower().Replace("- ", "-");
            Title = Title.ToLower().Replace(" -", "-");
            Title = Title.ToLower().Replace("   ", " ");
            Title = Title.ToLower().Replace("  ", " ");
            Title = Title.ToLower().Replace("  ", " ");
            Title = Title.ToLower().Replace(" ", " ");
            Title = Title.ToLower().Replace(" ", "-");
            return Title;
        }
    }
}