namespace SabaNews.Livescore.Services.Criteria.Match
{
    using Fanex.Data.Repository;

    public class GetMatchDetailCriteria : CriteriaBase
    {
        public int MatchId { get; set; }

        public int SportId { get; set; } = 1;

        public override string GetSettingKey() => "Livescore_Match_GetDetail";

        public override bool IsValid() => true;
    }
}
