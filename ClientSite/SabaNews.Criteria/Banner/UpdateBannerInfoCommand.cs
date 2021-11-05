using System;

namespace SabaNews.Criteria.Banner
{
    public class UpdateBannerInfoCommand : BaseNonQueryCommand
    {
        public string NaviagationUrl { get; set; }

        public string DesktopUrl { get; set; }

        public string MobileUrl { get; set; }

        public int ActionId { get; set; }

        public int ActorId { get; set; }

        public DateTime UpdatedDate { get; set; }

        public override string GetSettingKey() => "banner_update";

        public override bool IsValid() => true;
    }
}
