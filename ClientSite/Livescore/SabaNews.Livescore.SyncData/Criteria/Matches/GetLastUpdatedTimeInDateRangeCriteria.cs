namespace SabaNews.Livescore.SyncData.Criteria.Matches
{
    using System;
    using Fanex.Data.Repository;

    public class GetLastUpdatedTimeInDateRangeCriteria : CriteriaBase
    {
        public DateTime? StartTime { get; set; }

        public DateTime? EndTime { get; set; }

        public bool ExcludeLiveMatch { get; set; }

        public override string GetSettingKey() => "Livescore_SyncMatch_GetLastUpdatedTimeByDateRange";

        public override bool IsValid() => StartTime.HasValue && EndTime.HasValue;
    }
}