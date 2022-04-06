namespace SabaNews.Livescore.Services.Criteria.Match
{
    using Fanex.Data.Repository;

    public class GetMatchStatisticCriteria : CriteriaBase
    {
        public long MatchId { get; set; }

        public int SportId { get; set; } = 1;

        public override string GetSettingKey() => "Livescore_Match_GetStatistic";

        public override bool IsValid() => MatchId > 0;
    }
}
