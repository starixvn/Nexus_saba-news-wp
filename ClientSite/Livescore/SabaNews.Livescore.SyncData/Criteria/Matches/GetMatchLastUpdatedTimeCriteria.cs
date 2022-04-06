namespace SabaNews.Livescore.SyncData.Criteria.Matches
{
    using Fanex.Data.Repository;

    public class GetMatchLastUpdatedTimeCriteria : CriteriaBase
    {
        public override string GetSettingKey() => "Livescore_Match_GetLastUpdatedTime";

        public override bool IsValid() => true;
    }
}