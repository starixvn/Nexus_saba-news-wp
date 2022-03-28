namespace SabaNews.Livescore.SyncData.Services
{
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using Newtonsoft.Json;
    using SabaNews.Core.Models;
    using SabaNews.Livescore.Core.Enum;
    using SabaNews.Livescore.Provider.API.Models.Season;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.SyncData.Criteria.Season;
    using SabaNews.Services;

    public class SyncSeasonService : ISyncSeasonService
    {
        private readonly AppSettings appSettings;
        private readonly IBaseService baseService;
        private readonly IFetchSeasonService fetchSeasonService;

        public SyncSeasonService(
            AppSettings appSettings,
            IBaseService baseService,
            IFetchSeasonService fetchSeasonService)
        {
            this.appSettings = appSettings;
            this.baseService = baseService;
            this.fetchSeasonService = fetchSeasonService;
        }

        public async Task SyncSeasonListByLastUpdatedTimeAsync()
        {
            DateTime? lastUpdatedTime = await GetLastUpdatedTimeAsync();
            var param = new FetchSeasonListParams
            {
                LastUpdatedTime = lastUpdatedTime ?? appSettings.MinDateSyncLivescoreData,
                RowLimit = appSettings.BatchSizeSyncLivescoreData
            };
            List<SeasonListResponse> seasons = await fetchSeasonService.FetchSeasonListAsync(param);
            if (seasons.Any(x => x.SportId == (short)SportType.Soccer))
            {
                var json = JsonConvert.SerializeObject(seasons.Where(x => x.SportId == (short)SportType.Soccer).ToList());
                var command = new ImportSeasonCommand
                {
                    SeasonListJson = json
                };
                await baseService.ExecuteNonQueryAsync(command);
            }
        }

        protected internal virtual async Task<DateTime?> GetLastUpdatedTimeAsync()
            => await baseService.GetDataEntityAsync<DateTime?>(new GetSeasonLastUpdatedTimeCriteria());
    }
}