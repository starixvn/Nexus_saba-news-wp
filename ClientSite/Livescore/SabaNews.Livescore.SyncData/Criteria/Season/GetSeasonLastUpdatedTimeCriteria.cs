namespace SabaNews.Livescore.SyncData.Criteria.Season
{
    using Fanex.Data.Repository;

    public class GetSeasonLastUpdatedTimeCriteria : CriteriaBase
    {
        public override string GetSettingKey() => "Livescore_Sync_Season_GetLastUpdatedTime";

        public override bool IsValid() => true;
    }
}