namespace SabaNews.Livescore.SyncData.Services
{
    using System;
    using System.Linq;
    using System.Threading.Tasks;
    using Newtonsoft.Json;
    using SabaNews.Livescore.Core.Enum;
    using SabaNews.Livescore.Models.Synchronous;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.SyncData.Criteria.Matches;
    using SabaNews.Services;

    public class SyncMatchService : ISyncMatchService
    {
        private readonly IBaseService baseService;
        private readonly IFetchMatchService fetchMatchService;

        public SyncMatchService(IBaseService baseService,
            IFetchMatchService fetchMatchService)
        {
            this.baseService = baseService;
            this.fetchMatchService = fetchMatchService;
        }

        public async Task SyncAllMatchAsync()
        {
            var lastUpdatedTime = await GetMatchLastUpdatedTime();
            var param = new SyncMatchParams
            {
                SportId = (int)SportType.Soccer,
                LastUpdatedTime = lastUpdatedTime,
                RowLimit = 200
            };
            await SyncMatchAsync(param);
        }

        public async Task SyncMatchDetailAsync()
        {
            var matchIds = await baseService.GetDataAsync<long>(new GetTemporayMatchIdsCriteria());

            if (matchIds.Any())
            {
                var matches = await fetchMatchService.FetchMatchDetailAsync(new SyncMatchParams { MatchIds = matchIds.ToArray() });
                var command = new ImportMatchDetailCommand { MatchDetailJson = JsonConvert.SerializeObject(matches) };
                await baseService.ExecuteNonQueryAsync(command);
            }
        }

        private async Task SyncMatchAsync(SyncMatchParams syncMatchParams)
        {
            var matches = await fetchMatchService.FetchMatchListAsync(syncMatchParams);

            if (matches.Any())
            {
                var json = JsonConvert.SerializeObject(matches);
                var command = new ImportMatchCommand
                {
                    MatchListJson = json
                };

                await baseService.ExecuteNonQueryAsync(command);
            }
        }

        private async Task<DateTime?> GetMatchLastUpdatedTime()
            => await baseService.GetDataEntityAsync<DateTime?>(new GetMatchLastUpdatedTimeCriteria());
    }
}