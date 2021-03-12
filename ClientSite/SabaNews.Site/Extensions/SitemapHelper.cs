namespace SabaNews.Site.Extensions
{
    using System;
    using System.Collections.Generic;
    using System.Xml.Linq;
    using SabaNews.Models;

    public static class SitemapHelper
    {
        public static string GetSitemapDocument(IEnumerable<SitemapModel> sitemapNodes)
        {
            XNamespace xmlns = "http://www.sitemaps.org/schemas/sitemap/0.9";
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
    }
}