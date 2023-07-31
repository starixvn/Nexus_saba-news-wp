namespace SabaNews.Site.Extensions
{
    using HtmlAgilityPack;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using SabaNews.Models;
    using SabaNews.Models.Categories;
    using SabaNews.Models.Posts;
    using SabaNews.Models.StoryPost;
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
                link.Append($"{cate.Slug}/");
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

                parentName = currentSlug.MenuTitle;

        public static (MenuItems, string) GetMenuActive(List<Menu> menus, string cateSlug) {
            var listCate = new List<MenuInfo>();
            var cate = menus.LastOrDefault(item => item.CateSlug == cateSlug);
            if(cate != null)
            {
                listCate.Add(new MenuInfo { PostId = cate.PostId, CateSlug = cate.CateSlug  });
            }
            var currentCate = menus.LastOrDefault(item => item.PostId == cate?.ParentId);
            while(currentCate != null  || listCate.Count > 3)
            {
                listCate.Add(new MenuInfo { PostId = currentCate.PostId, CateSlug = currentCate.CateSlug  });
                currentCate = menus.LastOrDefault(item => item.PostId == currentCate.ParentId);
            }
            var activeMenu = listCate.Count >= 1 ? listCate[listCate.Count - 1].CateSlug : null;
            var menuItems = new MenuItems
            {
                Item1 = listCate.Count >= 1 ? listCate[listCate.Count - 1].PostId : 0,
                Item2 = listCate.Count >= 2 ? listCate[listCate.Count - 2].PostId : 0,
                Item3 = listCate.Count >= 3 ? listCate[listCate.Count - 3].PostId : 0
            };
            return (menuItems, activeMenu);

        }

        public static string GetTopCateParent(IList<CategoryGeneral> cates)
        {
            var parents = cates.Where(item => item.Slug != Constants.NewPostSlug).ToList();
            return parents.Any() ? parents.FirstOrDefault()?.Slug : cates.FirstOrDefault()?.Slug;
        }

        public static List<StoryPostModel> GetListImageFromPostContent(string postContent)
        {
            var stories = new List<StoryPostModel>();
            HtmlDocument document = new HtmlDocument();
            document.LoadHtml(postContent);
            var nodes = document.DocumentNode.SelectNodes("//div[contains(@class, 'wp-block-image')]/figure");
            if (nodes == null)
            { 
                nodes = document.DocumentNode.SelectNodes("figure");
                if (nodes == null)
                {
                    return stories;
                }
            }
            foreach (var item in nodes)
            {
                var figcaption = item.SelectSingleNode("figcaption");
                var img = item.SelectSingleNode("img").Attributes["src"].Value;
                var title = "";
                var link = "";
                if (figcaption != null)
                {
                    title = figcaption.InnerHtml;
                    if (figcaption.SelectSingleNode("a") != null)
                    {
                        link = figcaption.SelectSingleNode("a").Attributes["href"].Value;
                        title = figcaption.SelectSingleNode("a").InnerHtml;
                    }
                }
                stories.Add(new StoryPostModel { ImgSrc = img, Title = title, Link = link });
            }
            return stories;
        }

        public static string GetMatchInfoFromContent(string postContent)
        {
            string matchId = null;
            HtmlDocument document = new HtmlDocument();
            document.LoadHtml(postContent);
            var matchNode = document.DocumentNode.SelectSingleNode("//div[contains(@id, 'match-detail')]");
            if(matchNode != null) {
                matchId = matchNode.Attributes["data-id"].Value;
            }
            return matchId;
        }

    }
}
