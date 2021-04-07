namespace SabaNews.Site.Extensions
{
    using System;
    using System.Collections.Generic;
    using System.Xml.Linq;
    using SabaNews.Models;

    public static class SitemapHelper
    {
        private static readonly XNamespace xmlns = "http://www.sitemaps.org/schemas/sitemap/0.9";
        private static readonly XNamespace xmlns_news = "http://www.google.com/schemas/sitemap-news/0.9";

        public static string GetSitemapDocument(IEnumerable<SitemapModel> sitemapNodes)
        {
            XElement root = new(xmlns + "urlset");

            foreach (SitemapModel sitemapNode in sitemapNodes)
            {
                XElement urlElement = new(
                    xmlns + "url",
                    new XElement(xmlns + "loc", Uri.EscapeUriString(sitemapNode.Url)),
                    sitemapNode.ModifiedDate == null ? null : new XElement(
                        xmlns + "lastmod",
                        sitemapNode.ModifiedDate.Value.ToLocalTime().ToString("yyyy-MM-ddTHH:mm:sszzz")));
                root.Add(urlElement);
            }

            XDocument document = new(root);
            return document.ToString();
        }

        public static string GetSitemapNews(IEnumerable<NewsSitemapModel> sitemapNodes, string domain)
        {
            XElement root = new(xmlns + "urlset",
                    new XAttribute(XNamespace.Xmlns + "news", xmlns_news));

            foreach (NewsSitemapModel sitemapNode in sitemapNodes)
            {
                XElement publicationElement = new(
                    xmlns_news + "publication",
                    new XElement(xmlns_news + "name", "Saba Sports"),
                    new XElement(xmlns_news + "language", "vi"));
                XElement newsElement = new(
                    xmlns_news + "news",
                    publicationElement,
                    new XElement(xmlns_news + "publication_date", 
                        sitemapNode.ModifiedDate.Value.ToLocalTime().ToString("yyyy-MM-ddTHH:mm:sszzz")),
                    new XElement(xmlns_news + "title", sitemapNode.Title),
                    new XElement(xmlns_news + "keywords", sitemapNode.Tags));
                XElement urlE = new(
                    xmlns + "url",
                    new XElement(xmlns + "loc", Uri.EscapeUriString($"{domain}/{sitemapNode.Slug}")),
                    newsElement);
                root.Add(urlE);
            }

            XDocument document = new(root);
            return document.ToString();
        }
    }
}