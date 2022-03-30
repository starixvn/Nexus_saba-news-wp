namespace SabaNews.Livescore.Provider.API.Models.Matches
{
    using System.Collections.Generic;
    using Newtonsoft.Json;

    public class MatchCommentaryResponse
    {
        [JsonProperty("tliveFt")]
        public List<MatchCommentary> Commentaries { get; set; }
    }

    public class MatchCommentary
    {
        public long MatchId { get; set; }
        public string Time { get; set; }
        public string Data { get; set; }
    }
}