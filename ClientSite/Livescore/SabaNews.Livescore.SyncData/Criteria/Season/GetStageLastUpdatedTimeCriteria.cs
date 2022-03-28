namespace SabaNews.Livescore.SyncData.Criteria.Season
{
    using Fanex.Data.Repository;

    public class GetStageLastUpdatedTimeCriteria : CriteriaBase
    {
        public override string GetSettingKey() => "Livescore_Sync_Stage_GetLastUpdatedTime";

        public override bool IsValid() => true;
    }
}