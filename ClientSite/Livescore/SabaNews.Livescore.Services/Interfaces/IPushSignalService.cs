namespace SabaNews.Livescore.Services.Interfaces
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Livescore.Models.Match;

    public interface IPushSignalService
    {
        public Task PushMatchListAsync(IEnumerable<Match> matches);

        public Task PushMatchDetailcAsync(long matchId, MatchDetail detail);
    }
}