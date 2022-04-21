namespace SabaNews.Livescore.SyncData.Services
{
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using Newtonsoft.Json;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Provider.API.Models.Matches;
    using SabaNews.Livescore.SyncData.Criteria.Matches;
    using SabaNews.Livescore.SyncData.Helpers;

    public class SyncMatchService : ISyncMatchService
    {
        private readonly IBaseService baseService;

        public SyncMatchService(IBaseService baseService)
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

        public async Task SyncMatchDetailsAsync(List<MatchDetailResponse> matchDetails)
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

        public async Task<DateTime> GetMatchLastUpdatedTimeAsync()
            => await baseService.GetDataEntityAsync<DateTime>(new GetMatchLastUpdatedTimeCriteria());

        public async Task<long> GetMaxMatchIdAsync()
        => await baseService.GetDataEntityAsync<long>(new GatMatchMaxIdCriteria());

        public async Task SyncDeletedMatchesAsync(List<DeletedMatch> delMatches)
        {
            if (delMatches.Any())
            {
                var command = new ImportDeletedMatchCommand
                {
                    DeletedMatchJson = JsonConvert.SerializeObject(delMatches)
                };

                await baseService.ExecuteNonQueryAsync(command);
            }
        }
    }
}