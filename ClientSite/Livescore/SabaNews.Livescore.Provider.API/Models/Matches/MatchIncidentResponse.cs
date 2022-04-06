namespace SabaNews.Livescore.Provider.API.Models.Matches
{
    using System.Collections.Generic;
    using Newtonsoft.Json;

    public class MatchIncidentResponse
    {
        [JsonProperty("incidents")]
        public List<MatchIncident> Incidents { get; set; }
    }

    public class MatchIncident
    {
        public long MatchId { get; set; }
        public int Type { get; set; }
        public int Position { get; set; }
        public int Time { get; set; }
        public int Second { get; set; }
        public int HomeScore { get; set; }
        public int AwayScore { get; set; }
        public long PlayerId { get; set; }
        public string PlayerName { get; set; }
        public long AssistId { get; set; }
        public string AssistName { get; set; }
        public long InPlayerId { get; set; }
        public string InPlayerName { get; set; }
        public long OutPlayerId { get; set; }
        public string OutPlayerName { get; set; }
        public int VarReason { get; set; }
        public int VarResult { get; set; }
        public int ReasonType { get; set; }
    }
}