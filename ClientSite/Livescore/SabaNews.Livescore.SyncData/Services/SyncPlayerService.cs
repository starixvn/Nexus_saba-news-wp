namespace SabaNews.Livescore.SyncData.Services
{
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using Newtonsoft.Json;
    using SabaNews.Core.Models;
    using SabaNews.Livescore.Core.Enum;
    using SabaNews.Livescore.Provider.API.Models.Player;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.SyncData.Criteria.Player;
    using SabaNews.Services;

    public class SyncPlayerService : ISyncPlayerService
    {
        private readonly AppSettings appSettings;
        private readonly IBaseService baseService;
        private readonly IFetchPlayerService fetchPlayerService;

        public SyncPlayerService(
            AppSettings appSettings,
            IBaseService baseService,
            IFetchPlayerService fetchPlayerService)
        {
            this.appSettings = appSettings;
            this.baseService = baseService;
            this.fetchPlayerService = fetchPlayerService;
        }

        public async Task SyncPlayerListByLastUpdatedTimeAsync()
        {
            DateTime? lastUpdatedTime = await baseService.GetDataEntityAsync<DateTime?>(new GetPlayerLastUpdatedTimeCriteria());
            var param = new FetchPlayerListParams
            {
                SportId = (short)SportType.Soccer,
                LastUpdatedTime = lastUpdatedTime ?? appSettings.MinDateSyncLivescoreData,
                RowLimit = appSettings.BatchSizeSyncLivescoreData
            };
            IDictionary<string, List<PlayerListResponse>> dictionary = await fetchPlayerService.FetchPlayerListAsync(param);
            if (dictionary.Any())
            {
                var players = new List<PlayerListResponse>();
                foreach (KeyValuePair<string, List<PlayerListResponse>> entry in dictionary)
                {
                    _ = long.TryParse(entry.Key, out long teamId);
                    foreach (var player in entry.Value)
                    {
                        player.TeamId = teamId;
                        players.Add(player);
                    }
                }
                var json = JsonConvert.SerializeObject(players);
                var command = new ImportPlayerCommand
                {
                    PlayerListJson = json
                };
                await baseService.ExecuteNonQueryAsync(command);
            }
        }
    }
}