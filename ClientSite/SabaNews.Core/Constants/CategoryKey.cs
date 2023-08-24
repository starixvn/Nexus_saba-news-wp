using System.Collections.Generic;
using SabaNews.Core.Models;

namespace SabaNews.Core.Constants
{
    public static class CategoryKey
    {
        public const string Football = "bong-da";

        public const string Vietnam = "viet-nam";

        public const string Europe = "cup-chau-au";

        public const string England = "anh";

        public const string TBN = "tay-ban-nha";

        public const string Italia = "italia";

        public const string France = "phap";

        public const string Germany = "duc";

        public const string International = "quoc-te";

        public const string Prefix = "category-";

        public const string Suffix = "-sabavn";

        public const int ShowLivescore = 1;
        public const int HideLivescore = 0;

        public static readonly IReadOnlyList<CateSlugRedirect> CateRedirectList = new List<CateSlugRedirect>
        {
            new CateSlugRedirect { OldSlug = "viet-nam-sabavn", NewSlug = "bong-da-viet-nam-sabavn" }
        };
    }
}