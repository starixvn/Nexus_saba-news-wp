namespace SabaNews.Livescore.SyncData.Criteria.HeadToHead
{
    using System;
    using Fanex.Data.Repository;

    public class GetMatchToSyncH2HCriteria : CriteriaBase
    {
        public DateTime? FromDate { get; set; }
        public DateTime? ToDate { get; set; }
        public int RowLimit { get; set; }

        public override string GetSettingKey() => "Livescore_SyncHeadToHead_GetMatchToSync";

        public override bool IsValid()
            => FromDate is not null &&
                ToDate is not null;
    }
}