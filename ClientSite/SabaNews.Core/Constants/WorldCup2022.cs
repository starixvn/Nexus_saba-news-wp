namespace SabaNews.Core.Constants
{
    using SabaNews.Core.Models;
    using System.Collections.Generic;

    public static class WorldCup2022
    {
        public const string Homepage = "Trang chủ";

        public const string HomepageSlug = "world-cup-2022";

        public const string LastestNews = "Tin mới nhất";

        public const string LastestNewsSlug = "tin-tuc-world-cup-2022";

        public const string Fixture = "Lịch thi đấu";

        public const string FixtureSlug = "lich-thi-dau-world-cup-2022";

        public const string Standing = "Bảng xếp hạng";

        public const string StandingSlug = "bang-xep-hang-world-cup-2022";

        public const string Commentary = "Nhận định";

        public const string CommentarySlug = "nhan-dinh-world-cup-2022";

        public const string Video = "Video";

        public const string VideoSlug = "video-world-cup-2022";

        public const string Minigame = "Minigame";

        public const string MinigameSlug = "minigame-world-cup-2022";

        public const long LeagueIdWorldCup2022 = 14638;
        public const long SeasonIdWorldCup2022 = 10810;

        public static readonly List<CateInfo> Menus = new List<CateInfo>
        {
            new CateInfo { Name = Homepage, Slug = "" },
            new CateInfo { Name = LastestNews, Slug = LastestNewsSlug },
            new CateInfo { Name = Fixture, Slug = FixtureSlug },
            new CateInfo { Name = Standing, Slug = StandingSlug },
            new CateInfo { Name = Commentary, Slug = CommentarySlug },
            new CateInfo { Name = Video, Slug = VideoSlug },
            new CateInfo { Name = Minigame, Slug = MinigameSlug }
        };

        public static readonly List<CateInfo> Cates = new List<CateInfo>
        {
            new CateInfo { Name = Homepage, Slug = HomepageSlug, Url = $"/{HomepageSlug}" },
            new CateInfo { Name = LastestNews, Slug = LastestNewsSlug, Url = $"/{HomepageSlug}/{LastestNewsSlug}" },
            new CateInfo { Name = Commentary, Slug = CommentarySlug, Url = $"/{HomepageSlug}/{CommentarySlug}" },
            new CateInfo { Name = Video, Slug = VideoSlug, Url = $"/{HomepageSlug}/{VideoSlug}" },
        };
    }
}
