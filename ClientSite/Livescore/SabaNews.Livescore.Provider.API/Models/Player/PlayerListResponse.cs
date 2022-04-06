namespace SabaNews.Livescore.Provider.API.Models.Player
{
    using System;

    public class PlayerListResponse
    {
        public long TeamId { get; set; }
        public long PlayerId { get; set; }
        public short? Weight { get; set; }
        public short? Height { get; set; }
        public DateTime? Birthday { get; set; }
        public string Name { get; set; }
        public string FullName { get; set; }
        public string NameEN { get; set; }
        public string FullNameEN { get; set; }
        public string Icon { get; set; }
        public short? ShirtNumber { get; set; }
        public long? CountryId { get; set; }
        public string Nationality { get; set; }
        public string Description { get; set; }
        public DateTime UpdatedAt { get; set; }
    }
}