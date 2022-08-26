namespace SabaNews.Site.Extensions
{
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using SabaNews.Models.Categories;
    using System.Collections.Generic;
    using System.Linq;
    using System.Text;

    public static class PostHelper
    {
        public static string GetCategoryLink(IList<CategoryGeneral> cates)
        {
            var link = new StringBuilder();
            foreach (var cate in cates)
            {
                link.Append($"/{cate.Slug}");
            }
            return link.ToString();
        }

        public static bool CheckIsVideo(IList<CategoryGeneral> cates)
        {
            var video = cates.Where(cate => cate.Slug == Constants.VideoSlug).ToList();
            return video.Any();

        }

        public static List<CateInfo> GetEventCates()
        {
            var cates = new List<CateInfo>();
            cates.AddRange(WorldCup2022.Cates);
            return cates;
        }

        public static string GetTopCateParent(IList<CategoryGeneral> cates)
        {
            var parents = cates.Where(item => item.Slug != Constants.NewPostSlug).ToList();
            return parents.Any() ? parents.FirstOrDefault().Slug : cates.FirstOrDefault().Slug;
        }

    }
}
