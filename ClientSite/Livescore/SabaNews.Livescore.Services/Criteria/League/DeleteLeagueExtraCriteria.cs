namespace SabaNews.Livescore.Services.Criteria.League
{
    using SabaNews.Core.Models;

    public class DeleteLeagueExtraCriteria : BaseNonQueryCommand
    {
        public int LeagueId { get; set; }
        public override string GetSettingKey() => "livescore_league_extra_remove";

        public override bool IsValid() => true;
    }
}
