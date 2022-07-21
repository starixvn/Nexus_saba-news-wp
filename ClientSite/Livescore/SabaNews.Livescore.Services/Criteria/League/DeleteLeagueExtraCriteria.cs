namespace SabaNews.Livescore.Services.Criteria.League
{
    using SabaNews.Core.Models;

    public class DeleteLeagueExtraCriteria : BaseNonQueryCommand
    {
        public int LeagueId { get; set; }
        public override string GetSettingKey() => "Livescore_League_Extra_Remove";

        public override bool IsValid() => true;
    }
}
