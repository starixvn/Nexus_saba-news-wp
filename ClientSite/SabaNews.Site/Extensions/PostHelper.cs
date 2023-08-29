namespace SabaNews.Site.Extensions
{
    using HtmlAgilityPack;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using SabaNews.Models;
    using SabaNews.Models.Categories;
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

        public static bool IsVideo(IList<CategoryGeneral> cates)
        {
            var video = cates.Where(cate => cate.Slug == Constants.VideoSlug).ToList();
            return video.Any();

        }

        public static bool IsCommentary(IList<CategoryGeneral> cates)
        {
            var cate = cates.Where(cate => cate.Slug == CategoryKey.GuessSlug).ToList();
            return cate.Any();
        }

        public static List<CateInfo> GetEventCates()
        {
            var cates = new List<CateInfo>();
            cates.AddRange(WorldCup2022.Cates);
            return cates;
        }

        public static MenuCategoryModel GetMenuActive(List<Menu> menus, IList<CategoryGeneral> cateParent, string cateSlug) {
            var listCate = new List<MenuInfo>();
            var parentSlug = "";
            if(IsVideo(cateParent))
            {
                parentSlug = CategoryKey.VideoSlug;
            }
            else if(IsCommentary(cateParent))
            {
                parentSlug = CategoryKey.GuessSlug;
            }
            if(parentSlug != "" && cateSlug != parentSlug)
            {
                var parentCate = menus.FirstOrDefault(item => item.CateSlug == parentSlug && item.ParentId == 0);
                listCate = GetSubMenuForCate(menus, parentCate, cateSlug);
            }
            else
            {
                listCate = GetParentMenuForCate(menus, cateSlug);
            }
            var (menuItems, activeMenu, currentName) = GetMenuItems(listCate);
            var result = new MenuCategoryModel
            {
                MenuItems = menuItems,
                ActiveMenu = activeMenu,
                CurrentName = currentName,
            };
            return result;

        }

        public static (MenuItems, string, string) GetMenuItems(List<MenuInfo> listCate)
        {
            string currentName = listCate.Count > 0 ? listCate.First().Name : "";
            var activeMenu = listCate.Count >= 1 ? listCate[0].CateSlug : null;
            var menuItems = new MenuItems
            {
                Item1 = listCate.Count >= 1 ? listCate[0].PostId : 0,
                Item2 = listCate.Count >= 2 ? listCate[1].PostId : 0,
                Item3 = listCate.Count >= 3 ? listCate[2].PostId : 0
            };
            return (menuItems, activeMenu, currentName);
        }

        public static List<MenuInfo> GetSubMenuForCate(List<Menu> menus, Menu parentCate, string cateSlug)
        {
            var listCate = new List<MenuInfo>();
            if(parentCate == null)
            {
                return listCate;
            }
            listCate.Add(new MenuInfo { PostId = parentCate.PostId, CateSlug = parentCate.CateSlug, Name = parentCate.MenuTitle  });
            foreach(var menu in menus.Where(item => item.ParentId == parentCate.PostId))
            {
                if(menu.CateSlug == cateSlug)
                {
                    listCate.Add(new MenuInfo { PostId = menu.PostId, CateSlug = menu.CateSlug, Name = menu.MenuTitle  });
                    break;
                }
                foreach(var submenu in menus.Where(item => item.ParentId == menu.PostId))
                {
                    if(submenu.CateSlug == cateSlug)
                    {
                        listCate.Add(new MenuInfo { PostId = menu.PostId, CateSlug = menu.CateSlug, Name = menu.MenuTitle  });
                        listCate.Add(new MenuInfo { PostId = submenu.PostId, CateSlug = submenu.CateSlug, Name = submenu.MenuTitle  });
                        break;
                    }
                }
            }
            if(listCate.Count() == 1)
            {
                return new List<MenuInfo>();
            }
            return listCate;
        }

        public static List<MenuInfo> GetParentMenuForCate(List<Menu> menus, string cateSlug)
        {
            var listCate = new List<MenuInfo>();
            var cate = menus.LastOrDefault(item => item.CateSlug == cateSlug);
            if(cate != null)
            {
                listCate.Add(new MenuInfo { PostId = cate.PostId, CateSlug = cate.CateSlug, Name = cate.MenuTitle  });
            }
            var currentCate = menus.LastOrDefault(item => item.PostId == cate?.ParentId);
            while(currentCate != null  || listCate.Count > 3)
            {
                listCate.Add(new MenuInfo { PostId = currentCate.PostId, CateSlug = currentCate.CateSlug, Name = currentCate.MenuTitle  });
                currentCate = menus.LastOrDefault(item => item.PostId == currentCate.ParentId);
            }
            listCate.Reverse();
            return listCate;
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
