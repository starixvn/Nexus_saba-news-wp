namespace SabaNews.Livescore.SyncData.Services.Interfaces
{
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Livescore.Provider.API.Models.Team;
    using SabaNews.Livescore.SyncData.Models.HeadToHead;

    public interface ISyncHeadToHeadService
    {
        public Task<IEnumerable<MatchToGetH2H>> GetMatchToSyncHeadToHeadByDateRange(DateTime from, DateTime to, int limit);

        public Task UpdateSyncCompletedHeadToHead(long matchId);

        Task SyncHeadToHeadAsync(HeadToHeadResponse h2hResponse);
    }
}