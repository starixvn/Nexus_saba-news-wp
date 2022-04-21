namespace SabaNews.Livescore.Provider.API.Models.Matches
{
    using System.Collections.Generic;

    public class DeletedMatchResponse
    {
        public IEnumerable<DeletedMatch> DeletedMatches { get; set; }
    }

    public class DeletedMatch
    {
        public long MatchId { get; set; }
    }
}