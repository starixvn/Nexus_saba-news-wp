namespace SabaNews.Livescore.Services.Criteria.Match
{
    using System;
    using Fanex.Data.Repository;

    public class GetMatchByStageCriteria : CriteriaBase
    {
        public short SportId { get; set; }
        public long LeagueId { get; set; }
        public byte LeagueType { get; set; }
        public long SeasonId { get; set; }
        public long StageId { get; set; }
        public DateTime? FromTime { get; set; }
        public DateTime? ToTime { get; set; }

        public override string GetSettingKey() => "Livescore_Match_GetByStage";

        public override bool IsValid() => true;
    }
}