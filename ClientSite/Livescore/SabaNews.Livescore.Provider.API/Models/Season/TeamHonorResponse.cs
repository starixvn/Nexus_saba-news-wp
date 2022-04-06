namespace SabaNews.Livescore.Provider.API.Models.Season
{
    using System;

    public class TeamHonorResponse
    {
        public long Id { get; set; }
        public long HonorId { get; set; }
        public long? SeasonId { get; set; }
        public string Year { get; set; }
        public long? TeamId { get; set; }
        public string TeamName { get; set; }
        public long? LeagueId { get; set; }
        public string LeagueName { get; set; }
        public DateTime UpdatedAt { get; set; }
    }
}