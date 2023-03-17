namespace SabaNews.Models.Banner
{
    using SabaNews.Core.Constants;

    public class BannerPost
    {
        public string MobileTopUrl { get; set; }
        public string MobileTopUrlKey { get; set; } = BannerKey.PostMobileTopUrl;
        public string MobileTopNavigationUrl { get; set; }
        public string MobileTopNavigationUrlKey { get; set; } = BannerKey.PostMobileTopNavigation;
        public string MobileCenterUrl { get; set; }
        public string MobileCenterUrlKey { get; set; } = BannerKey.PostMobileCenterUrl;
        public string MobileCenterNavigationUrl { get; set; }
        public string MobileCenterNavigationUrlKey { get; set; } = BannerKey.PostMobileCenterNavigation;
        public string MobileBottomUrl { get; set; }
        public string MobileBottomUrlKey { get; set; } = BannerKey.PostMobileBottomUrl;
        public string MobileBottomNavigationUrl { get; set; }
        public string MobileBottomNavigationUrlKey { get; set; } = BannerKey.PostMobileBottomNavigation;
    }
}
