using System.Collections.Generic;
using SabaNews.Core.Models;

namespace SabaNews.Core.Constants
{
    public class Euro2024
    {
        public const string Homepage = "Trang chủ";
        public const string TitleHomepage = "Euro 2024: Tin tức, Lịch thi đấu, Kết quả, Bảng xếp hạng - SABA Sports";

        public const string Fixture = "Lịch thi đấu";
        public const string TitleFixture = "Lịch thi đấu World Cup 2022 - SABA Sports";

        public const string FixtureSlug = "lich-thi-dau-world-cup-2022";
        public const string UrlFixtureSlug = "lich-thi-dau";

        public const string Standing = "Bảng xếp hạng";
        public const string TitleStanding = "Bảng xếp hạng World Cup 2022 - SABA Sports";

        public const string StandingSlug = "bang-xep-hang-world-cup-2022";
        public const string UrlStandingSlug = "bang-xep-hang";

        public const string HomepageSlug = "euro-2024";
        public const string LastestNews = "Tin mới nhất";
        public const string TitleLastestNews = "Tin mới nhất Euro 2024 hôm nay - SABA Sports";

        public const string LastestNewsSlug = "tin-tuc-world-cup-2022";
        public const string UrlLastestNewsSlug = "tin-moi-nhat";

        public const string Commentary = "Nhận định";
        public const string TitleCommentary = "Nhận định World Cup 2022 hôm nay - SABA Sports";

        public const string CommentarySlug = "nhan-dinh-euro-2024";
        public const string UrlCommentarySlug = "nhan-dinh";

        public const string Video = "Video";
        public const string TitleVideo = "Video World Cup 2022 hôm nay - SABA Sports";

        public const string VideoSlug = "video-euro-2024";
        public const string UrlVideoSlug = "video";

        public const string VideoHighlightSlug = "video-highlights-world-cup-2022";

        public const string VideoGuessSlug = "video-du-doan-world-cup-2022";

        public const string Overview = "Tổng quan";
        public const string OverviewPageSlug = "danh-sach-san-thi-dau-vong-chung-ket-euro-2020";
        public const string OverviewRoute = "tong-quan";


        public const long LeagueId = 12015;
        public const long SeasonId = 11334;
        public const long GroupStageId = 35455;

        public const long EightTeamStage  = 35537;
        public const long QuarterfinalsStage = 35539;
        public const long SemifinalsStage = 35538;
        public const long FinalStage = 35540;
        
        
        public const string PollKey = "poll_euro_2024";
        public const int PollTheme = 1;


        public static readonly IReadOnlyList<CateInfo> Menus = new List<CateInfo>
        {
            new() { Name = Homepage, Slug = "" },
            new() { Name = LastestNews, Slug = UrlLastestNewsSlug },
            new() { Name = Fixture, Slug = UrlFixtureSlug },
            new() { Name = Standing, Slug = UrlStandingSlug },
            new() { Name = Commentary, Slug = UrlCommentarySlug },
            new() { Name = Video, Slug = UrlVideoSlug }
        };

        public static readonly IReadOnlyList<CateInfo> CateSlugs = new List<CateInfo>
        {
            new() { Name = Homepage, Slug = HomepageSlug, Url = $"/{HomepageSlug}" },
            new() { Name = LastestNews, Slug = LastestNewsSlug, Url = $"/{HomepageSlug}/{UrlLastestNewsSlug}" },
            new() { Name = Commentary, Slug = CommentarySlug, Url = $"/{HomepageSlug}/{UrlCommentarySlug}" },
            new() { Name = Video, Slug = VideoSlug, Url = $"/{HomepageSlug}/{UrlVideoSlug}" }
        };

        public static readonly IReadOnlyList<KeyValue> TeamProfiles = new List<KeyValue>
        {
            new() { Id = 33172, Name = "doi-tuyen-bong-da-albania" },
            new() { Id = 35611, Name = "doi-tuyen-bong-da-anh" },
            new() { Id = 33152, Name = "doi-tuyen-bong-da-quoc-gia-ao-tai-euro" },
            new() { Id = 33151, Name = "doi-tuyen-bong-da-ba-lan" },
            new() { Id = 33157, Name = "doi-tuyen-bong-da-bi" },
            new() { Id = 33169, Name = "doi-tuyen-bong-da-bo-dao-nha" },
            new() { Id = 33149, Name = "doi-tuyen-bong-da-quoc-gia-croatia-tai-euro" },
            new() { Id = 33163, Name = "doi-tuyen-bong-da-ch-sec" },
            new() { Id = 33124, Name = "doi-tuyen-bong-da-dan-mach" },
            new() { Id = 33144, Name = "doi-tuyen-bong-da-duc-tu-thang-tram-qua-khu-den-euro-2024" },
            new() { Id = 33587, Name = "doi-tuyen-bong-da-georgia" },
            new() { Id = 33155, Name = "doi-tuyen-bong-da-ha-lan" },
            new() { Id = 33136, Name = "doi-tuyen-bong-da-hungary" },
            new() { Id = 33734, Name = "doi-tuyen-bong-da-italy" },
            new() { Id = 45378, Name = "doi-tuyen-bong-da-phap" },
            new() { Id = 36617, Name = "doi-tuyen-bong-da-romania" },
            new() { Id = 33154, Name = "doi-tuyen-bong-da-scotland" },
            new() { Id = 33168, Name = "doi-tuyen-bong-da-serbia" },
            new() { Id = 33148, Name = "doi-tuyen-bong-da-slovakia" },
            new() { Id = 33150, Name = "doi-tuyen-bong-da-slovenia" },
            new() { Id = 33125, Name = "doi-tuyen-bong-da-tay-ban-nha" },
            new() { Id = 33122, Name = "doi-tuyen-bong-da-thuy-si" },
            new() { Id = 33171, Name = "doi-tuyen-bong-da-tho-nhi-ky" },
            new() { Id = 33166, Name = "doi-tuyen-bong-da-ukraine" },

        };
    }
}