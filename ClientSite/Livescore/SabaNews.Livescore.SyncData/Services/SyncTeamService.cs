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
    using SabaNews.Livescore.Provider.API.Models.Team;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.SyncData.Criteria.Team;

    public class SyncTeamService : ISyncTeamService
    {
        private readonly AppSettings appSettings;
        private readonly IBaseService baseService;
        private readonly IFetchTeamService fetchTeamService;

        public SyncTeamService(
            AppSettings appSettings,
            IBaseService baseService,
            IFetchTeamService fetchTeamService)
        {
            this.appSettings = appSettings;
            this.baseService = baseService;
            this.fetchTeamService = fetchTeamService;
        }

        public async Task SyncTeamListByLastUpdatedTimeAsync()
        {
            DateTime? lastUpdatedTime = await GetLastUpdatedTimeAsync();
            var param = new FetchTeamListParams
            {
                SportId = (short)SportType.Soccer,
                LastUpdatedTime = lastUpdatedTime ?? appSettings.MinDateSyncLivescoreData,
                RowLimit = appSettings.BatchSizeSyncLivescoreData
            };
            List<TeamListResponse> teams = await fetchTeamService.FetchTeamListAsync(param);
            ////teams = teams.Where(x => !string.IsNullOrWhiteSpace(x.TeamNameEN) || !string.IsNullOrWhiteSpace(x.TeamFullNameEN)).ToList();
            if (teams.Any())
            {
                var json = JsonConvert.SerializeObject(teams);
                var command = new ImportTeamCommand
                {
                    TeamListJson = json
                };
                await baseService.ExecuteNonQueryAsync(command);
            }
        }

        public async Task SyncTeamListGreaterThanTeamIdAsync()
        {
            long? maxId = await GetMaxTeamIdAsync();
            var param = new FetchTeamListParams
            {
                SportId = (short)SportType.Soccer,
                IdGte = maxId ?? 0,
                RowLimit = appSettings.BatchSizeSyncLivescoreData
            };
            List<TeamListResponse> teams = await fetchTeamService.FetchTeamListAsync(param);
            ////teams = teams.Where(x => !string.IsNullOrWhiteSpace(x.TeamNameEN) || !string.IsNullOrWhiteSpace(x.TeamFullNameEN)).ToList();
            if (teams.Any())
            {
                var json = JsonConvert.SerializeObject(teams);
                var command = new ImportTeamCommand
                {
                    TeamListJson = json
                };
                await baseService.ExecuteNonQueryAsync(command);
            }
        }

        protected internal virtual async Task<DateTime?> GetLastUpdatedTimeAsync()
            => await baseService.GetDataEntityAsync<DateTime?>(new GetTeamLastUpdatedTimeCriteria());

        protected internal virtual async Task<long?> GetMaxTeamIdAsync()
            => await baseService.GetDataEntityAsync<long?>(new GetMaxTeamIdCriteria());
    }
}