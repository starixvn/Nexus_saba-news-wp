namespace SabaNews.Livescore.SyncData.Services
{
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using Newtonsoft.Json;
    using SabaNews.Livescore.Provider.API.Models.Matches;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.SyncData.Criteria.Matches;
    using SabaNews.Livescore.SyncData.Helpers;
    using SabaNews.Livescore.SyncData.Models.Matches;
    using SabaNews.Services;

    public class SyncMatchService : ISyncMatchService
    {
        private readonly IBaseService baseService;

        public SyncMatchService(IBaseService baseService,
            IFetchMatchService fetchMatchService)
        {
            this.baseService = baseService;
        }

        public async Task SyncMatchByLastUpdatedTimeAsync(List<MatchResponse> matches)
        {
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

        public async Task SyncMatchDetailByIdsAsync(List<MatchDetailResponse> matchDetails)
        {
            if (matchDetails.Any())
            {
                var (matchScoreList, statisticList) = DataHelpers.GenerateMatchDetailInput(matchDetails);
                var command = new ImportMatchDetailCommand
                {
                    MatchScoreJson = JsonConvert.SerializeObject(matchScoreList),
                    MatchDetailJson = JsonConvert.SerializeObject(statisticList)
                };
                await baseService.ExecuteNonQueryAsync(command);
            }
        }

        public async Task SyncMatchLineupByIdsAsync(List<MatchLineupResponse> matchLineups)
        {
            if (matchLineups is not null && matchLineups.Any())
            {
                var (lineups, players) = DataHelpers.GenerateMatchLineupInput(matchLineups);
                var command = new ImportMatchLineupCommand
                {
                    MatchLineupJson = JsonConvert.SerializeObject(lineups),
                    LineupPlayerJson = JsonConvert.SerializeObject(players)
                };

                await baseService.ExecuteNonQueryAsync(command);
            }
        }

        public async Task SyncMultipleMatchCommentaryAsync(List<MatchCommentary> commentaries)
        {
            if (commentaries.Any())
            {
                var command = new ImportCommentaryCommand
                {
                    CommentaryJson = JsonConvert.SerializeObject(commentaries)
                };

                await baseService.ExecuteNonQueryAsync(command);
            }
        }

        public async Task SyncMultipleMatchIncidentAsync(List<MatchIncident> incidents)
        {
            if (incidents.Any())
            {
                var command = new ImportIncidentCommand
                {
                    IncidentJson = JsonConvert.SerializeObject(incidents)
                };
                await baseService.ExecuteNonQueryAsync(command);
            }
        }

        public async Task<IEnumerable<TemporaryMatch>> GetTemporaryMatchToFetchInfo(int rowLimit)
        {
            var tmpMatches = await baseService.GetDataAsync<TemporaryMatch>(
                new GetTemporayMatchesCriteria
                {
                    RowLimit = rowLimit
                });
            return tmpMatches;
        }

        public async Task RemoveMatchesFromTemporaryTable(long[] matchIds)
        {
            if (matchIds.Length > 0)
            {
                var command = new DeleteTemporaryMatchIdCommand
                {
                    MatchIds = string.Join(",", matchIds)
                };
                await baseService.ExecuteNonQueryAsync(command);
            }
        }

        public async Task InsertTemporaryMatch(long[] matchIds)
        {
            if (matchIds.Length > 0)
            {
                var command = new InsertTemporaryMatchCommand
                {
                    MatchIds = string.Join(",", matchIds)
                };

                await baseService.ExecuteNonQueryAsync(command);
            }
        }

        public async Task<DateTime> GetMatchLastUpdatedTime()
            => await baseService.GetDataEntityAsync<DateTime>(new GetMatchLastUpdatedTimeCriteria());
    }
}