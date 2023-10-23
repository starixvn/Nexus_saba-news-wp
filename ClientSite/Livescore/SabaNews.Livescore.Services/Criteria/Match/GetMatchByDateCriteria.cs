namespace SabaNews.Livescore.Services.Criteria.Match
{
    using System;
    using Fanex.Data;
    using Fanex.Data.Repository;

    public class GetMatchByDateCriteria : CriteriaBase
    {
        [SpParam(Ignored = true)]
        public DateTimeOffset EventDate { get; set; }

        [SpParam(Ignored = true)]
        public float Timezone { get; set; }

        public DateTimeOffset FromTime { get; set; }
        public DateTimeOffset ToTime { get; set; }

        public short SportId { get; set; }

        public override string GetSettingKey() => "Livescore_Match_GetByDate";

        public override bool IsValid() => true;
    }
}