namespace SabaNews.Livescore.Services.Criteria.League
{
    using SabaNews.Core.Models;

    public class InsertLeagueExtraCriteria : BaseNonQueryCommand
    {
        public int LeagueId { get; set; }
        public int Priority { get; set; }
        public bool IsMajor { get; set; }
        public override string GetSettingKey() => "livescore_league_extra_insert";

        public override bool IsValid() => true;
    }
}
