namespace SabaNews.Livescore.Provider.API.Models.Matches
{
    using Newtonsoft.Json;
    using System.Collections.Generic;

    public class MatchIncidentUpdateTimeResponse
    {
        [JsonProperty("updatedMatches")]
        public List<long> UpdatedMatches { get; set; }
    }
}
