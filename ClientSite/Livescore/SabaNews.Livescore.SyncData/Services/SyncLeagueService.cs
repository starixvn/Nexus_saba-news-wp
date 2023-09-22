namespace SabaNews.Livescore.SyncData.Services
{
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using Newtonsoft.Json;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Core.Enum;
    using SabaNews.Livescore.Provider.API.Models.League;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.SyncData.Criteria.League;

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

        public async Task SyncLeagueListByLastUpdatedTimeAsync()
        {
            DateTime? lastUpdatedTime = await GetLastUpdatedTimeAsync();
            var param = new FetchLeagueListParams
            {
                SportId = (short)SportType.Soccer,
                LastUpdatedTime = lastUpdatedTime ?? appSettings.MinDateSyncLivescoreData,
                RowLimit = appSettings.BatchSizeSyncLivescoreData
            };
            List<LeagueListResponse> leagues = await fetchLeagueService.FetchLeagueListAsync(param);
            ////leagues = leagues.Where(x => !string.IsNullOrWhiteSpace(x.LeagueNameEN) || !string.IsNullOrWhiteSpace(x.LeagueFullNameEN)).ToList();
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

        public async Task SyncLeagueListGreaterThanLeagueIdAsync()
        {
            long? maxLeagueId = await GetMaxLeagueIdAsync();
            var param = new FetchLeagueListParams
            {
                SportId = (short)SportType.Soccer,
                IdGte = maxLeagueId ?? 0,
                RowLimit = appSettings.BatchSizeSyncLivescoreData
            };
            List<LeagueListResponse> leagues = await fetchLeagueService.FetchLeagueListAsync(param);
            ////leagues = leagues.Where(x => !string.IsNullOrWhiteSpace(x.LeagueNameEN) || !string.IsNullOrWhiteSpace(x.LeagueFullNameEN)).ToList();
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

        public async Task SyncLeagueSeasonByIdsAsync(long?[] leagueIds)
        {
            var param = new FetchLeagueListParams
            {
                SportId = (short)SportType.Soccer,
                LeagueIds = leagueIds,
                RowLimit = appSettings.BatchSizeSyncLivescoreData
            };
            List<LeagueListResponse> leagues = await fetchLeagueService.FetchLeagueListAsync(param);
            if(leagues.Any())
            {
                var leagueSeasonData = leagues.Where(l => l.CurSeasonId != null && l.CurSeasonId != 0).Select(i => new { i.LeagueId, i.CurSeasonId, i.Seasons }).ToArray();
                var json = JsonConvert.SerializeObject(leagueSeasonData);
                var command = new ImportLeagueSeasonCommand
                {
                    LeagueListJson = json
                };
                await baseService.ExecuteNonQueryAsync(command);
            }
        }

        protected internal virtual async Task<DateTime?> GetLastUpdatedTimeAsync()
            => await baseService.GetDataEntityAsync<DateTime?>(new GetLeagueLastUpdatedTimeCriteria());

        protected internal virtual async Task<long?> GetMaxLeagueIdAsync()
            => await baseService.GetDataEntityAsync<long?>(new GetMaxLeagueIdCriteria());
    }
}