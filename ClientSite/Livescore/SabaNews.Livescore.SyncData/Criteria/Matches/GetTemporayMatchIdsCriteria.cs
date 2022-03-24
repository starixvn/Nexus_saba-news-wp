namespace SabaNews.Livescore.SyncData.Criteria.Matches
{
    using Fanex.Data.Repository;

    public class GetTemporayMatchIdsCriteria : CriteriaBase
    {
        public override string GetSettingKey() => "Livescore_Match_GetTemporaryIds";

        public override bool IsValid() => true;
    }
}