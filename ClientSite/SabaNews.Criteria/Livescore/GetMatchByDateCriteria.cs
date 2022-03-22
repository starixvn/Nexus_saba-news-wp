namespace SabaNews.Criteria.Livescore
{
    using System;
    using Fanex.Data.Repository;

    public class GetMatchByDateCriteria : CriteriaBase
    {
        public DateTime EventDate { get; set; }
        public short SportId { get; set; }

        public override string GetSettingKey() => "Livescore_Match_GetByDate";

        public override bool IsValid() => true;
    }
}