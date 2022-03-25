namespace SabaNews.Livescore.SyncData.Services
{
    using System;
    using System.Linq;
    using System.Threading.Tasks;
    using Newtonsoft.Json;
    using SabaNews.Core.Models;
    using SabaNews.Livescore.Core.Enum;
    using SabaNews.Livescore.Provider.API.Models.League;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.SyncData.Criteria.League;
    using SabaNews.Services;

    public class SyncLeagueService : ISyncLeagueService
    {
        private readonly AppSettings appSettings;
        private readonly IBaseService baseService;
        private readonly IFetchLeagueService fetchLeagueService;

        public SyncLeagueService(
            AppSettings appSettings,
            IBaseService baseService,
            IFetchLeagueService fetchLeagueService)
        {
            this.appSettings = appSettings;
            this.baseService = baseService;
            this.fetchLeagueService = fetchLeagueService;
        }

        public async Task SyncLeagueListAsync()
        {
            const short RowLimit = 1000;
            var lastUpdatedTime = await GetLastUpdatedTimeAsync();
            var param = new FetchLeagueParams
            {
                SportId = (short)SportType.Soccer,
                LastUpdatedTime = lastUpdatedTime ?? appSettings.MinDateSyncLivescoreData,
                RowLimit = RowLimit
            };
            var leagues = await fetchLeagueService.FetchLeagueListAsync(param);
            if (leagues.Any())
            {
                var json = JsonConvert.SerializeObject(leagues);
                var command = new ImportLeagueCommand
                {
                    LeagueListJson = json
                };

                await baseService.ExecuteNonQueryAsync(command);
            }
        }

        protected internal virtual async Task<DateTime?> GetLastUpdatedTimeAsync()
               => await baseService.GetDataEntityAsync<DateTime?>(new GetLeagueLastUpdatedTimeCriteria());
    }
}