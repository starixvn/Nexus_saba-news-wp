namespace SabaNews.Livescore.Models.Mqtt
{
    using System.Collections.Generic;
    using Newtonsoft.Json;

    public class MqttTextLiveIncidents
    {
        public long MatchId { get; set; }

        [JsonProperty("tlive")]
        public List<MqttTextLive> TextLive { get; set; }

        [JsonProperty("iccidents")]
        public List<MqttIncidents> Incidents { get; set; }
    }

    public class MqttTextLive
    {
        public int Main { get; set; }
        public int Position { get; set; }
        public string Type { get; set; }
        public string Data { get; set; }
        public string Time { get; set; }
    }

    public class MqttIncidents
    {
        public string Type { get; set; }
        public int Position { get; set; }
        public int Time { get; set; }
        public int HomeScore { get; set; }
        public int AwayScore { get; set; }
        public long PlayerId { get; set; }
        public long InPlayerId { get; set; }
        public string InPlayerName { get; set; }
        public long OutPlayerId { get; set; }
        public string OutPlayerName { get; set; }
        public string PlayerName { get; set; }
        public long AssistId { get; set; }
        public string AssistName { get; set; }
        public int VarReason { get; set; }
        public int VarResult { get; set; }
        public int ReasonType { get; set; }
    }
}