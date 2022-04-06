namespace SabaNews.Livescore.SyncData.Criteria.Matches
{
    using Fanex.Data.Repository;

    public class GetTemporayMatchesCriteria : CriteriaBase
    {
        public int RowLimit { get; set; }

        public override string GetSettingKey() => "Livescore_Match_Temporary_Get";

        public override bool IsValid() => RowLimit > 0 && RowLimit <= 100;
    }
}