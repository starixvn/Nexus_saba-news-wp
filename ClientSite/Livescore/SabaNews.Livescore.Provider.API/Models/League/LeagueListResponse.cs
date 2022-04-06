namespace SabaNews.Livescore.Provider.API.Models.League
{
    using System;

    public class LeagueListResponse
    {
        public long LeagueId { get; set; }
        public long? CombinedId { get; set; }
        public short SportId { get; set; }
        public string LeagueName { get; set; }
        public string LeagueFullName { get; set; }
        public string LeagueNameEN { get; set; }
        public string LeagueFullNameEN { get; set; }
        public DateTime UpdatedAt { get; set; }
        public int Level { get; set; }
        public string Icon { get; set; }
        public byte? LeagueType { get; set; }
        public short? CurRound { get; set; }
        public short? RoundCount { get; set; }
        public string TitleHolderTeam { get; set; }
        public string TitleHolderCount { get; set; }
        public string MostTitleTeam { get; set; }
        public string MostTitlesCount { get; set; }
        public long? CategoryId { get; set; }
        public long? CountryId { get; set; }
        public long? CurStageId { get; set; }
        public string CurStageNameEN { get; set; }
        public string Ruledesc { get; set; }
        public long? CurSeasonId { get; set; }
        public object Seasons { get; set; }
        public string Country { get; set; }
        public string City { get; set; }
        public long?[] HigherDivisions { get; set; }
        public long?[] LowerDivisions { get; set; }
        public long?[] LowerNewcomers { get; set; }
        public long?[] HigherNewcomers { get; set; }
    }
}