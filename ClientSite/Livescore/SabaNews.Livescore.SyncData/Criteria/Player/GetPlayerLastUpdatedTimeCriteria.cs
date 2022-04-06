namespace SabaNews.Livescore.SyncData.Criteria.Player
{
    using Fanex.Data.Repository;

    public class GetPlayerLastUpdatedTimeCriteria : CriteriaBase
    {
        public override string GetSettingKey() => "Livescore_Sync_Player_GetLastUpdatedTime";

        public override bool IsValid() => true;
    }
}