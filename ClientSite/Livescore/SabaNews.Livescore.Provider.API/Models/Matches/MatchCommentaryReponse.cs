namespace SabaNews.Livescore.Provider.API.Models.Matches
{
    using System.Collections.Generic;
    using Newtonsoft.Json;

    public class MatchCommentaryReponse
    {
        [JsonProperty("tliveFt")]
        public List<MatchCommentary> Incidents { get; set; }
    }

    public class MatchCommentary
    {
        public string Time { get; set; }
        public string Data { get; set; }
    }
}