namespace SabaNews.Criteria.Banner
{
    public class UpdateBannerInfoCommand : BaseNonQueryCommand
    {
        public string NaviagationUrl { get; set; }

        public string DesktopUrl { get; set; }

        public string MobileUrl { get; set; }

        public override string GetSettingKey() => "banner_update";

        public override bool IsValid() => !string.IsNullOrEmpty(NaviagationUrl);
    }
}
