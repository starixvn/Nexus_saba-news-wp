namespace SabaNews.Livescore.SyncData.Criteria.Team
{
    using Fanex.Data.Repository;

    public class GetTeamLastUpdatedTimeCriteria : CriteriaBase
    {
        public override string GetSettingKey() => "Livescore_Sync_Team_GetLastUpdatedTime";

        public override bool IsValid() => true;
    }
}