namespace SabaNews.Livescore.Services.Interfaces
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Livescore.Models.Match;

    public interface IPushSignalService
    {
        public Task PushToMatchListAsync(IEnumerable<Match> matches);
    }
}