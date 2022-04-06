namespace SabaNews.Livescore.Services.Criteria.Match
{
    using Fanex.Data.Repository;

    public class GetMatchDetailPostMatchCriteria : CriteriaBase
    {
        public long MatchId { get; set; }

        public int SportId { get; set; } = 1;

        public override string GetSettingKey() => "Livescore_Match_GetDetail_PostMatch";

        public override bool IsValid() => true;
    }
}