using Microsoft.AspNetCore.Http;

namespace SabaNews.Models.ManagementLeague
{
    public class LeagueUpdateViewModel
    {
        public long LeagueId { get; set; }
        public bool IsMajor { get; set; }
        public int Priority { get; set; }
        public bool IsDeletedIcon { get; set; }
        public string LeagueNameVN { get; set; }
        public IFormFile Icon { get; set; }
        public string CountryVN { get; set; }
    }
}
