namespace SabaNews.Livescore.Models.Match
{
    using System;

    public class GetMatchByStageParams
    {
        public short SportId { get; set; }
        public long LeagueId { get; set; }
        public long SeasonId { get; set; }
        public long StageId { get; set; }
        public DateTime Date { get; set; }
        public float Timezone { get; set; }
    }
}