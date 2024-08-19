namespace SabaNews.Livescore.Provider.API.Models.Season
{
    using System;

    public class StageListResponse
    {
        public short SportId { get; set; }
        public long? SeasonId { get; set; }
        public long StageId { get; set; }
        public string StageName { get; set; }
        public string StageNameEN { get; set; }
        public byte? Mode { get; set; }
        public short? GroupCount { get; set; }
        public short? RoundCount { get; set; }
        public short OrderIndex { get; set; }
        public bool IsDelete { get; set; }
        public DateTime UpdatedAt { get; set; }
    }
}