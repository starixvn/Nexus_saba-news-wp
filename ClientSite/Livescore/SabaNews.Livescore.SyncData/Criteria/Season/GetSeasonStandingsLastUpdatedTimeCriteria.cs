namespace SabaNews.Livescore.SyncData.Criteria.Season
{
    using Fanex.Data.Repository;

    public class GetSeasonStandingsLastUpdatedTimeCriteria : CriteriaBase
    {
        public override string GetSettingKey() => "Livescore_Sync_SeasonStandings_GetLastUpdatedTime";

        public override bool IsValid() => true;
    }
}