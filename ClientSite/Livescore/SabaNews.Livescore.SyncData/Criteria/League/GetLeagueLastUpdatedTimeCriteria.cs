namespace SabaNews.Livescore.SyncData.Criteria.League
{
    using Fanex.Data.Repository;

    public class GetLeagueLastUpdatedTimeCriteria : CriteriaBase
    {
        public override string GetSettingKey() => "Livescore_Sync_League_GetLastUpdatedTime";

        public override bool IsValid() => true;
    }
}