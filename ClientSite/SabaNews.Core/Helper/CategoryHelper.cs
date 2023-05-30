namespace SabaNews.Core.Helper
{
    using SabaNews.Core.Models.Categories;
    using System.Collections.Generic;
    using System.Linq;
    using System.Text;

    public static class CategoryHelper
    {

        public static string GetCategoryLink(IList<CategoryGeneral> cates)
        {
            var link = new StringBuilder();
            foreach (var cate in cates)
            {
                link.Append($"{cate.Slug}/");
            }
            return link.ToString();
        }

        public static bool CheckIsVideo(IList<CategoryGeneral> cates)
        {
            var video = cates.Where(cate => cate.Slug == Constants.Constants.VideoSlug).ToList();
            return video.Any();

        }
    }
}
