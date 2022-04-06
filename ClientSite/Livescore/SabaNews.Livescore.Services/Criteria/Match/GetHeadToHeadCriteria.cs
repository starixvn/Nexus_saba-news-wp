namespace SabaNews.Livescore.Services.Criteria.Match
{
    using Fanex.Data.Repository;

    public class GetHeadToHeadCriteria : CriteriaBase
    {
        public int HomeTeamId { get; set; }

        public int AwayTeamId { get; set; }

        public override string GetSettingKey() => "Livescore_Match_GetHeadToHead";

        public override bool IsValid() => HomeTeamId > 0 && AwayTeamId > 0;


    }
}
