namespace SabaNews.Livescore.SyncData.Criteria.Season
{
    using Fanex.Data.Repository;

    public class GetSeasonPlayerHonorLastUpdatedTimeCriteria : CriteriaBase
    {
        public override string GetSettingKey() => "Livescore_Sync_SeasonPlayerHonor_GetLastUpdatedTime";

        public override bool IsValid() => true;
    }
}