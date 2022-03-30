namespace SabaNews.Livescore.SyncData.Criteria.Season
{
    using Fanex.Data.Repository;

    public class GetSeasonTeamHonorLastUpdatedTimeCriteria : CriteriaBase
    {
        public override string GetSettingKey() => "Livescore_Sync_SeasonTeamHonor_GetLastUpdatedTime";

        public override bool IsValid() => true;
    }
}