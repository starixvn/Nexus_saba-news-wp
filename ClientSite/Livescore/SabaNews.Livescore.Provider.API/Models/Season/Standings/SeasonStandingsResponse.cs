namespace SabaNews.Livescore.Provider.API.Models.Season
{
    using System.Collections.Generic;

    public class SeasonStandingsResponse
    {
        public long? StageId { get; set; }
        public string StageName { get; set; }
        public IList<ScoreTableResponse> Group { get; set; }
    }
}