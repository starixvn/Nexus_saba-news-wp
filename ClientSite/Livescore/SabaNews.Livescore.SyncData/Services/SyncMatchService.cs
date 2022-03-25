namespace SabaNews.Livescore.SyncData.Services
{
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using Newtonsoft.Json;
    using SabaNews.Livescore.Core.Enum;
    using SabaNews.Livescore.Models.Synchronous;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.SyncData.Criteria.Matches;
    using SabaNews.Livescore.SyncData.Helpers;
    using SabaNews.Livescore.SyncData.Models.Matches;
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

        public async Task SyncMatchDetailByIdsAsync(long[] matchIds)
        {
            if (matchIds.Any())
            {
                var matches = await fetchMatchService.FetchMatchDetailAsync(new SyncMatchParams { MatchIds = matchIds.ToArray() });

                var (matchScoreList, statisticList) = GenerateMatchDetailInput(matches);
                var command = new ImportMatchDetailCommand
                {
                    MatchScoreJson = JsonConvert.SerializeObject(matchScoreList),
                    MatchDetailJson = JsonConvert.SerializeObject(statisticList)
                };
                await baseService.ExecuteNonQueryAsync(command);
            }
        }

        private static (List<MatchScoreInput>, List<MatchTeamStatistic>) GenerateMatchDetailInput(List<MatchDetailResponse> matches)
        {
            var matchScoreList = new List<MatchScoreInput>();
            var statisticList = new List<MatchTeamStatistic>();
            foreach (var match in matches)
            {
                var matchScore = new MatchScoreInput
                {
                    MatchId = match.MatchId,
                    HomeHalfScore = match.HHalfScore ?? 0,
                    AwayHalfScore = match.AHalfScore ?? 0,
                    HomeTotalScore = match.HTotalScore ?? 0,
                    AwayTotalScore = match.ATotalScore ?? 0,
                    HomeAggregateScore = match.HomeAggScore,
                    AwayAggregateScore = match.AwayAggScore
                };
                matchScoreList.Add(matchScore);
                var homeStatistic = DataHelpers.ConvertToTeamStatistic(match.ExtraData, match.MatchId, true);
                var awayStatistic = DataHelpers.ConvertToTeamStatistic(match.ExtraData, match.MatchId, false);
                statisticList.Add(homeStatistic);
                statisticList.Add(awayStatistic);
            }
            return (matchScoreList, statisticList);
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