namespace SabaNews.Livescore.SyncData.Services
{
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using Newtonsoft.Json;
    using SabaNews.Core.Helper;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Core.Enum;
    using SabaNews.Livescore.Models.Synchronous;
    using SabaNews.Livescore.Provider.API.Models.Matches;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.SyncData.Criteria.Matches;
    using SabaNews.Livescore.SyncData.Helpers;

    public class SyncMatchService : ISyncMatchService
    {
        private readonly IBaseService baseService;
        private readonly IFetchMatchService fetchMatchService;
        private readonly AppSettings appSettings;

        public SyncMatchService(IBaseService baseService, 
            IFetchMatchService fetchMatchService,
            AppSettings appSettings)
        {
            this.baseService = baseService;
            this.fetchMatchService = fetchMatchService;
            this.appSettings = appSettings;
        }

        public async Task SyncMatchByLastUpdatedTimeAsync(List<MatchResponse> matches, bool skipUpdatedAt = false)
        {
            if (matches.Any())
            {
                var json = JsonConvert.SerializeObject(matches);
                var command = new ImportMatchCommand
                {
                    MatchListJson = json,
                    SkipSyncUpdatedTime = skipUpdatedAt
                };

                await baseService.ExecuteNonQueryAsync(command);
            }
        }

        public async Task SyncMatchStatisticAsync(List<MatchDetailResponse> matchDetails)
        {
            if (matchDetails.Any())
            {
                var (matchScoreList, statisticList) = DataHelpers.GenerateMatchDetailInput(matchDetails);
                var command = new ImportMatchStatisticCommand
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
            if (commentaries is not null && commentaries.Any())
            {
                var command = new ImportCommentaryCommand
                {
                    CommentaryJson = JsonConvert.SerializeObject(commentaries)
                };

                await baseService.ExecuteNonQueryAsync(command);
            }
        }

        public async Task SyncMatchIncidentAsync(long matchId, List<MatchIncident> incidents)
        {
            if (incidents is not null && incidents.Any())
            {
                var command = new ImportIncidentCommand
                {
                    MatchId = matchId,
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
            if (delMatches is not null && delMatches.Any())
            {
                var command = new ImportDeletedMatchCommand
                {
                    DeletedMatchJson = JsonConvert.SerializeObject(delMatches)
                };

                await baseService.ExecuteNonQueryAsync(command);
            }
        }

        public async Task SyncAllMatchInfoByStartTimeRangeAndUpdatedTimeAsync(DateTime? startTime, DateTime? endTime, DateTime? lastUpdatedTime)
        {
            var param = new SyncMatchParams
            {
                SportId = (int)SportType.Soccer,
                StartTime = startTime,
                EndTime = endTime,
                LastUpdatedTime = lastUpdatedTime,
                RowLimit = appSettings.BatchSizeSyncMatches
            };
            var matches = await fetchMatchService.FetchMatchListAsync(param);

            if (matches is not null && matches.Any())
            {
                await SyncMatchByLastUpdatedTimeAsync(matches);

                await SyncMatchDetais(matches);
            }
        }

        public async Task SyncIncidentByUpdatedTimeAsync()
        {
            int SyncMinutes = appSettings.SyncIncidentMinutes;
            var lastUpdatedTime = await baseService.GetDataEntityAsync<DateTime?>(new GetIncidentLastUpdatedTimeCriteria());
            var now = DateTimeUtilize.UtcNow().DateTime;
            lastUpdatedTime ??= now.AddMinutes(-SyncMinutes);
            
            var updatedMatches = await fetchMatchService.FetchMatchIncidentUpdateTimeAsync((int)SportType.Soccer, lastUpdatedTime);
            await SyncIncidents(updatedMatches.ToArray());
            await baseService.ExecuteNonQueryAsync(new UpdateIncidentUpdatedTimeCommand { UpdatedTime = now });

        }

        public async Task<DateTime> GetMatchLastUpdatedTimeInDateRangeAsync(DateTime? startTime, DateTime? endTime, bool isExcludeLiveMatch = false)
        {
            var criteria = new GetLastUpdatedTimeInDateRangeCriteria
            {
                StartTime = startTime,
                EndTime = endTime,
                ExcludeLiveMatch = isExcludeLiveMatch
            };

            return await baseService.GetDataEntityAsync<DateTime>(criteria);
        }

        private async Task SyncMatchDetais(List<MatchResponse> matches)
        {
            var matchIds = matches.Select(m => m.MatchId).ToArray();

            await SyncMatchDetailByIdsAsync(matchIds);

            var syncLineupMatchIds = matches.Where(m => m.IsLineUp).Select(i => i.MatchId).ToArray();

            await SyncLinupAsync(syncLineupMatchIds);
        }

        private async Task SyncMatchDetailByIdsAsync(long[] matchIds)
        {
            var fetchMatchDetailParam = new SyncMatchParams
            {
                MatchIds = matchIds,
                RowLimit = appSettings.BatchSizeSyncMatches
            };
            var matches = await fetchMatchService.FetchMatchDetailAsync(fetchMatchDetailParam);

            await SyncMatchStatisticAsync(matches);
        }

        private async Task SyncLinupAsync(long[] matchIds)
        {
            if (matchIds is not null && matchIds.Any())
            {
                var lineups = await fetchMatchService.FetchMatchLineupAsync(matchIds);

                await SyncMatchLineupByIdsAsync(lineups);
            }
        }

        public async Task SyncIncidents(long[] matchIds)
        {
            const int limitFetchIncidents = 20;
            var batchMatchIds  = new List<long>();
            var count = 0;
            foreach (var matchId in matchIds)
            {
                count++;
                batchMatchIds.Add(matchId);
                if(batchMatchIds.Count == limitFetchIncidents || count == matchIds.Length)
                {
                    var dictionary = await fetchMatchService.FetchMultipleMatchIncidentsAsync((int)SportType.Soccer, batchMatchIds.ToArray());
                    if(dictionary != null)
                    {
                        await SyncMatchIncident(dictionary);
                    }
                    batchMatchIds = new List<long>();
                }
            }
        }

        private async Task SyncMatchIncident(IDictionary<string,  MatchIncidentResponse> dictionary)
        {
            foreach (KeyValuePair<string,  MatchIncidentResponse> entry in dictionary)
            {
                _ = long.TryParse(entry.Key, out long matchId);
                var value = entry.Value;
                if(value is not null && value.Incidents.Count > 0 )
                {
                    await SyncMatchIncidentAsync(matchId, value.Incidents);
                }
            }
        }

    }
}