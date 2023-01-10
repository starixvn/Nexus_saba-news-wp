namespace SabaNews.Models.Home
{
    public class UpdateFixtureModel
    {
        public int Id { get; set; }
        public long LeagueId { get; set; }
        public long SeasonId { get; set; }
        public string Year { get; set; }
        public int Order { get; set; }
    }
}
