using System.Collections.Generic;
using SabaNews.Core.Models;

namespace SabaNews.Core.Constants
{
    public static class CategoryKey
    {
        public const string Football = "bong-da";

        public const string Vietnam = "bong-da-viet-nam";

        public const string Europe = "giai-dau-cap-clb";

        public const string England = "bong-da-anh";

        public const string TBN = "bong-da-tbn";

        public const string Italia = "bong-da-italia";

        public const string France = "bong-da-phap";

        public const string Germany = "bong-da-duc";

        public const string International = "doi-tuyen";

        public const string NewPostSlug = "tin-the-thao";

        public const string GuessSlug = "nhan-dinh-bong-da";

        public const string BackstageSlug = "hau-truong";

        public const string TransferSlug = "chuyen-nhuong";

        public const string OtherPostSlug = "the-thao";

        public const string VideoSlug = "video";

        public const string VNFootballSlug = "bong-da-viet-nam";

        public const string ClubSlug = "giai-dau-cap-clb";

        public const string CountrySlug = "doi-tuyen";

        public const string AreaSlug = "khu-vuc";

        public const string Prefix = "category-";

        public const string Suffix = "-sabavn";

        public const string HotNewsTag = "diem-tin-bong-da-hom-nay";

        public const int ShowLivescore = 1;
        public const int HideLivescore = 0;

        public static readonly IReadOnlyList<CateSlugRedirect> CateRedirectList = new List<CateSlugRedirect>
        {
            new CateSlugRedirect { OldSlug = "viet-nam-sabavn", NewSlug = "bong-da-viet-nam-sabavn" },
            new CateSlugRedirect { OldSlug = "anh-sabavn", NewSlug = "bong-da-anh-sabavn" },
            new CateSlugRedirect { OldSlug = "tay-ban-nha-sabavn", NewSlug = "bong-da-tbn-sabavn" },
            new CateSlugRedirect { OldSlug = "cup-chau-au-sabavn", NewSlug = "giai-dau-cap-clb-sabavn" },
            new CateSlugRedirect { OldSlug = "champions-league-sabavn", NewSlug = "c1-champions-league-sabavn" },
            new CateSlugRedirect { OldSlug = "europa-league-sabavn", NewSlug = "c2-europa-league-sabavn" },
            new CateSlugRedirect { OldSlug = "conference-league-sabavn", NewSlug = "c3-conference-league-sabavn" },
            new CateSlugRedirect { OldSlug = "duc-sabavn", NewSlug = "bong-da-duc-sabavn" },
            new CateSlugRedirect { OldSlug = "italia-sabavn", NewSlug = "bong-da-italia-sabavn" },
            new CateSlugRedirect { OldSlug = "phap-sabavn", NewSlug = "bong-da-phap-sabavn" },
            new CateSlugRedirect { OldSlug = "quoc-te-sabavn", NewSlug = "doi-tuyen-sabavn" },
            new CateSlugRedirect { OldSlug = "cac-mon-khac-sabavn", NewSlug = "the-thao-sabavn" },
            new CateSlugRedirect { OldSlug = "nhan-dinh-cup-c1-sabavn", NewSlug = "nhan-dinh-c1-champions-league-sabavn" },
            new CateSlugRedirect { OldSlug = "nhan-dinh-cup-c2-sabavn", NewSlug = "nhan-dinh-c2-europa-league-sabavn" },
        };

        public static readonly Dictionary<string, string> LinkRedirectList = new()
        {
            { "/href=%22https://www.sabavn.net/sao-man-united-lap-cong-trong-ngay-dt-phap-gay-that-vong%22", "/sao-man-united-lap-cong-trong-ngay-dt-phap-gay-that-vong-53435.html" },
            { "/world-cup-2022/tin-the-thao/doi-tuyen/world-cup/", "/world-cup-sabavn" },
            { "/https://www.sabavn.net/juventus-chat-vat-thoat-thua-tren-san-nha", "/juventus-chat-vat-thoat-thua-tren-san-nha-183817.html" },
            { "/href=%22https://www.sabavn.net/bom-tan-man-united-gap-han-o-clb-lan-dtqg%22", "/bom-tan-man-united-gap-han-o-clb-lan-dtqg-53375.html" },
            { "/com/world-cup-2022/nhan-dinh/nhung-doi-bong-manh-de-bi-loai-som-o-world-cup-2022", "/nhung-doi-bong-manh-de-bi-loai-som-o-world-cup-2022-135631.html" },
            { "/%E2%80%9Dhttps://www.sabavn.net/chuyen-nhuong-mu-andre-onana-chuan-bi-mua-nha-o-manchester-ten-hag-sap-tai-ngo-tro-cung%22", "/chuyen-nhuong-mu-andre-onana-chuan-bi-mua-nha-o-manchester-ten-hag-sap-tai-ngo-tro-cung-200776.html" },
            { "/https://www.sabavn.net/cdv-tu-suong-tren-khan-dai-doi-mat-an-tu-1-nam", "/cdv-tu-suong-tren-khan-dai-doi-mat-an-tu-1-nam-58371.html" },
            { "/lich-thi-dau/bong-da/la-liga/rayo-vallecano-vs-real-madrid/58769716l", "/lich-thi-dau/bong-da/la-liga/rayo-vallecano-vs-real-madrid/58769716" },
            { "/href=%22https://www.sabavn.net/top-5-bom-tan-sap-no-tai-ngoai-hang-anh-nhung-ngoi-sao-tram-trieu%22", "/top-5-bom-tan-sap-no-tai-ngoai-hang-anh-nhung-ngoi-sao-tram-trieu-46298.html" },
            { "/%E2%80%9Dhttps://www.sabavn.net/goc-nhin-da-den-luc-cuoc-tinh-giua-liverpool-va-thiago-khep-lai%22", "/goc-nhin-da-den-luc-cuoc-tinh-giua-liverpool-va-thiago-khep-lai-200630.html" },
            { "/tin-tuc/bong-da/bong-da-trong-nuoc/", "/bong-da-viet-nam-sabavn" },
            { "/Home/Category", "/" },
            { "/https://www.sabavn.net/inter-milan-bai-tran-truoc-doi-bong-nuoc-phap", "/inter-milan-bai-tran-truoc-doi-bong-nuoc-phap-121015.html" }

        };
            

    }
}