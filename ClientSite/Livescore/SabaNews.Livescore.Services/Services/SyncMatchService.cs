namespace SabaNews.Livescore.Services.Services
{
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using Newtonsoft.Json;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.DatabaseStorage.Services.Interfaces;
    using SabaNews.Livescore.Provider.API.Models.Matches;
    using SabaNews.Livescore.Provider.API.Services.Interfaces;
    using SabaNews.Livescore.Services.Interfaces;

    public class SyncMatchService : ISyncMatchService
    {
        private readonly IFetchMatchService fetchMatchService;
        private readonly IStoreMatchService storeMatchService;
        private readonly ILogServiceProvider logServiceProvider;

        public SyncMatchService(IFetchMatchService fetchMatchService,
            IStoreMatchService storeMatchService,
            ILogServiceProvider logServiceProvider)
        {
            this.fetchMatchService = fetchMatchService;
            this.storeMatchService = storeMatchService;
            this.logServiceProvider = logServiceProvider;
        }

        public async Task SyncMatchByDateRangeAsync(DateTime fromDate, DateTime toDate)
        {
            logServiceProvider.WriteLogFileInfo("Start sync match by date range");
            var matches = await fetchMatchService.FetchMatchesByDateRangeAsync(fromDate, toDate);
            if (matches.Any())
            {
                var matchListJson = JsonConvert.SerializeObject(matches);
                var result = await storeMatchService.StoreMatchListAsync(matchListJson);
                logServiceProvider.WriteLogFileInfo($"Store match list result: {JsonConvert.SerializeObject(result)}");
            }
            logServiceProvider.WriteLogFileInfo("End sync match by date range");
        }

        public async Task SyncMatchByIdAsync(long matchId)
        {
            var match = await fetchMatchService.FetchMatchByIdAync(matchId);
            if (match is not null)
            {
                var matchListJson = JsonConvert.SerializeObject(new List<MatchResponse> { match });
                await storeMatchService.StoreMatchListAsync(matchListJson);
            }
        }
    }
}