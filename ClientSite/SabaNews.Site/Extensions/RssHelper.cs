namespace SabaNews.Site.Extensions
{
    using SabaNews.Core.Constants;
    using SabaNews.Models.Posts;
    using System;
    using System.Collections.Generic;
    using System.Xml.Linq;

    public static class RssHelper
    {
        private static readonly XNamespace xmlns_atom = "http://www.w3.org/2005/Atom";

        public static string GetRSSDocument(IEnumerable<Post> PostsInRss, string domain)
        {
            XElement root = new("rss", new XAttribute("version", "2.0"), new XAttribute(XNamespace.Xmlns + "atom", xmlns_atom));

            var channel = new XElement("channel",
                new XElement("title", Constants.SiteName),
                new XElement("link", domain),
                new XElement("description"),
                new XElement("language", "vi-vn"),
                new XElement("copyright", Constants.SiteName),
                new XElement("pubDate", DateTimeExtensions.ToDateGMTSEAsia(DateTime.Now)),
                new XElement("lastBuildDate", DateTimeExtensions.ToDateGMTSEAsia(DateTime.Now)),
                new XElement("image",
                    new XElement("url", $"{domain}images/sabasports.svg"),
                    new XElement("title", Constants.SiteName),
                    new XElement("link", domain)),
                new XElement(xmlns_atom + "link",
                    new XAttribute("href", $"{domain}home.rss"),
                    new XAttribute("rel", "self"), new XAttribute("type","application/rss+xml")));

            foreach(var item in PostsInRss)
            {
                var firstImgUrl = item.GetImageBySize(ImageWidth.Width300);
                var srcImg = firstImgUrl != null ? Uri.EscapeUriString(firstImgUrl) : string.Empty;
                var image = $"<img src=\"{srcImg}\" alt=\"{item.PostTitle}\" />";
                var description = $"<![CDATA[ <a href=\"{domain}{item.Slug}\">{image}</a></br >{item.PostExcerpt} ]]>";
                var itemElement = new XElement("item",
                    new XElement("title", item.PostTitle),
                    new XElement("description", description),
                    new XElement("link", $"{domain}{item.Slug}"),
                    new XElement("pubDate", DateTimeExtensions.ToDateGMTSEAsia(item.PostDateGMT)),
                    new XElement("guid", $"{domain}{item.Slug}"));
                channel.Add(itemElement);
            }
            root.Add(channel);
            XDocument document = new(root);
            return document.ToString();
        }
    }
}
