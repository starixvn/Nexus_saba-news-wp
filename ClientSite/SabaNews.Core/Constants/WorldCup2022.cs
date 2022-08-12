using SabaNews.Core.Models;
using System.Collections.Generic;

namespace SabaNews.Core.Constants
{
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

        public static readonly List<CateMenu> Menus = new List<CateMenu>
        {
            new CateMenu { Name = Homepage, Slug = "" },
            new CateMenu { Name = LastestNews, Slug = LastestNewsSlug },
            new CateMenu { Name = Fixture, Slug = FixtureSlug },
            new CateMenu { Name = Standing, Slug = StandingSlug },
            new CateMenu { Name = Commentary, Slug = CommentarySlug },
            new CateMenu { Name = Video, Slug = VideoSlug },
            new CateMenu { Name = Minigame, Slug = MinigameSlug }
        };
    }
}
