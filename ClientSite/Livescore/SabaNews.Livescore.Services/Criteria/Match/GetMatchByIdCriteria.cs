namespace SabaNews.Livescore.Services.Criteria.Match
{
    using Fanex.Data.Repository;

    public class GetMatchByIdCriteria : CriteriaBase
    {
        public long MatchId { get; set; }

        public int SportId { get; set; } = 1;

        public override string GetSettingKey() => "Livescore_Match_GetById";

        public override bool IsValid() => MatchId > 0;
    }
}
