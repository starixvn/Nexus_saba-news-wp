namespace SabaNews.Models.ManagementLeague
{
    public class LeagueUpdateViewModel
    {
        public long LeagueId { get; set; }
        public bool IsMajor { get; set; }
        public int Priority { get; set; }
        public string LeagueNameVN { get; set; }
        public string Country { get; set; }
        public string CountryVN { get; set; }
    }
}
