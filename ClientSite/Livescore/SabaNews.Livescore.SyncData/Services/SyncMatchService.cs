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
    using SabaNews.Livescore.SyncData.Helpers;
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
            await SyncMatchesAsync(param);
        }

        public async Task SyncMatchDetailByIdsAsync(long[] matchIds)
        {
            if (matchIds.Any())
            {
                var matches = await fetchMatchService.FetchMatchDetailAsync(new SyncMatchParams { MatchIds = matchIds.ToArray() });

                var (matchScoreList, statisticList) = DataHelpers.GenerateMatchDetailInput(matches);
                var command = new ImportMatchDetailCommand
                {
                    MatchScoreJson = JsonConvert.SerializeObject(matchScoreList),
                    MatchDetailJson = JsonConvert.SerializeObject(statisticList)
                };
                await baseService.ExecuteNonQueryAsync(command);
            }
        }

        public async Task SyncMatchLineupByIdsAsync(long[] matchIds)
        {
            var matchLineups = await fetchMatchService.FetchMatchLineupAsync(matchIds);

            var (lineups, players) = DataHelpers.GenerateMatchLineupInput(matchLineups);
            var command = new ImportMatchLineupCommand
            {
                MatchLineupJson = JsonConvert.SerializeObject(lineups),
                LineupPlayerJson = JsonConvert.SerializeObject(players)
            };

            await baseService.ExecuteNonQueryAsync(command);
        }

        public async Task SyncMatchCommentaryAsync(long matchId)
        {
            var commetaries = await fetchMatchService.FetchMatchCommentaryAsync(matchId);
            if (commetaries.Any())
            {
                var command = new ImportCommentaryCommand
                {
                    MatchId = matchId,
                    CommentaryJson = JsonConvert.SerializeObject(commetaries)
                };

                await baseService.ExecuteNonQueryAsync(command);
            }
        }

        public async Task SyncMatchIncidentAsync(int sportId, long matchId)
        {
            var incidents = await fetchMatchService.FetchMatchIncidentAsync(sportId, matchId);
            if (incidents.Any())
            {
                var command = new ImportIncidentCommand
                {
                    MatchId = matchId,
                    IncidentJson = JsonConvert.SerializeObject(incidents)
                };
                await baseService.ExecuteNonQueryAsync(command);
            }
        }

        private async Task<DateTime?> GetMatchLastUpdatedTime()
            => await baseService.GetDataEntityAsync<DateTime?>(new GetMatchLastUpdatedTimeCriteria());

        private async Task SyncMatchesAsync(SyncMatchParams syncMatchParams)
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
    }
}