namespace SabaNews.Livescore.Provider.API.Models.Team
{
    using System;

    public class TeamListResponse
    {
        public long TeamId { get; set; }
        public long? CombinedId { get; set; }
        public short SportId { get; set; }
        public string TeamName { get; set; }
        public string TeamFullName { get; set; }
        public string TeamNameEN { get; set; }
        public string TeamFullNameEN { get; set; }
        public long? LeagueId { get; set; }
        public long? CountryId { get; set; }
        public string Icon { get; set; }
        public string FoundationTime { get; set; }
        public string Website { get; set; }
        public long? MarketValue { get; set; }
        public string MarketValueCurrency { get; set; }
        public bool? National { get; set; }
        public string CountryLogo { get; set; }
        public short? TotalPlayers { get; set; }
        public short? ForeignPlayers { get; set; }
        public short? NationalPlayers { get; set; }
        public long? ManagerId { get; set; }
        public string ManagerNameEN { get; set; }
        public string ManagerIcon { get; set; }
        public long? VenueId { get; set; }
        public string VenueNameEN { get; set; }
        public int? VenueCapacity { get; set; }
        public string VenueCity { get; set; }
        public DateTime UpdatedAt { get; set; }
    }
}