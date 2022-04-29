namespace SabaNews.Livescore.SyncData.Services
{
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using Newtonsoft.Json;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Provider.API.Models.Team;
    using SabaNews.Livescore.SyncData.Criteria.HeadToHead;
    using SabaNews.Livescore.SyncData.Helpers;
    using SabaNews.Livescore.SyncData.Models.HeadToHead;
    using SabaNews.Livescore.SyncData.Services.Interfaces;

    public class SyncHeadToHeadService : ISyncHeadToHeadService
    {
        private readonly IBaseService baseService;

        public SyncHeadToHeadService(IBaseService baseService)
        {
            this.baseService = baseService;
        }

        public async Task<IEnumerable<MatchToGetH2H>> GetMatchToSyncHeadToHeadByDateRange(DateTime from, DateTime to, int limit)
        {
            var criteria = new GetMatchToSyncH2HCriteria
            {
                FromDate = from,
                ToDate = to,
                RowLimit = limit
            };

            return await baseService.GetDataAsync<MatchToGetH2H>(criteria);
        }

        public async Task SyncHeadToHeadAsync(HeadToHeadResponse h2hResponse)
        {
            if (h2hResponse is not null)
            {
                var dataImport = DataHelpers.GenerateTeamHistoryToImport(h2hResponse);

                var command = new ImportHeadToHeadCommand
                {
                    HeadToHeadJson = JsonConvert.SerializeObject(dataImport)
                };

                await baseService.ExecuteNonQueryAsync(command);
            }
        }

        public async Task UpdateSyncCompletedHeadToHead(long matchId)
        {
            var command = new UpdateSyncedH2HMatchList
            {
                MatchId = matchId
            };

            await baseService.ExecuteNonQueryAsync(command);
        }
    }
}