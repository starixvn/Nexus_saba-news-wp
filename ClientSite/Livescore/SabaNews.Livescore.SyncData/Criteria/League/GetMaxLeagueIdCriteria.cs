namespace SabaNews.Livescore.SyncData.Criteria.League
{
    using Fanex.Data.Repository;

    public class GetMaxLeagueIdCriteria : CriteriaBase
    {
        public override string GetSettingKey() => "Livescore_Sync_League_GetMaxId";

        public override bool IsValid() => true;
    }
}