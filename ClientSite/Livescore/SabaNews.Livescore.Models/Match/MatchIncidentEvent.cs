namespace SabaNews.Livescore.Models.Match
{
    public class MatchIncidentEvent
    {
        public int MatchId { get; set; }
        public int Type { get; set; }
        public int Position { get; set; }
        public int Time { get; set; }
        public int Second { get; set; }
        public int HomeScore { get; set; }
        public int AwayScore { get; set; }
        public int PlayerId { get; set; }
        public string PlayerName { get; set; }
        public string Content { get; set; }
        public int VarReason { get; set; }
        public int VarResult { get; set; }
        public int ReasonType { get; set; }
        public int AssistId { get; set; }
        public string AssistName { get; set; }
        public int InPlayerId { get; set; }
        public string InPlayerName { get; set; }
        public int OutPlayerId { get; set; }
        public string OutPlayerName { get; set; 
        }
    }
}
