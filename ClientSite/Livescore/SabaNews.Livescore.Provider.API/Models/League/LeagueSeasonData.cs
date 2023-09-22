namespace SabaNews.Livescore.Provider.API.Models.League
{
    public class LeagueSeasonData
    {
        public long LeagueId { get; set; }
        public long CurSeasonId { get; set; }
        public object Seasons { get; set; }
    }
}