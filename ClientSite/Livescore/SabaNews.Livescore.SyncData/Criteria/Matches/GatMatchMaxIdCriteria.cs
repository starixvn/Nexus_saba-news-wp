namespace SabaNews.Livescore.SyncData.Criteria.Matches
{
    using Fanex.Data.Repository;

    public class GatMatchMaxIdCriteria : CriteriaBase
    {
        public override string GetSettingKey() => "Livesocre_Match_GetMaxMatchId";

        public override bool IsValid() => true;
    }
}