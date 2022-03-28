namespace SabaNews.Livescore.SyncData.Criteria.Team
{
    using Fanex.Data.Repository;

    public class GetMaxTeamIdCriteria : CriteriaBase
    {
        public override string GetSettingKey() => "Livescore_Sync_Team_GetMaxId";

        public override bool IsValid() => true;
    }
}