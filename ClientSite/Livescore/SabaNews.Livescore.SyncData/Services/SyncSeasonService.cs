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
    using SabaNews.Livescore.Provider.API.Models.Season;
    using SabaNews.Livescore.Provider.API.Services;
    using SabaNews.Livescore.SyncData.Criteria.Season;
    using SabaNews.Livescore.SyncData.Models.Season;

    public class SyncSeasonService : ISyncSeasonService
    {
        private readonly AppSettings appSettings;
        private readonly IBaseService baseService;
        private readonly IFetchSeasonService fetchSeasonService;
        private readonly ISyncLeagueService syncLeagueService;

        public SyncSeasonService(
            AppSettings appSettings,
            IBaseService baseService,
            IFetchSeasonService fetchSeasonService,
            ISyncLeagueService syncLeagueService)
        {
            this.appSettings = appSettings;
            this.baseService = baseService;
            this.fetchSeasonService = fetchSeasonService;
            this.syncLeagueService = syncLeagueService;
        }

        public async Task SyncSeasonListByLastUpdatedTimeAsync()
        {
            DateTime? lastUpdatedTime = await baseService.GetDataEntityAsync<DateTime?>(new GetSeasonLastUpdatedTimeCriteria());
            var param = new FetchSeasonListParams
            {
                LastUpdatedTime = lastUpdatedTime ?? appSettings.MinDateSyncLivescoreData,
                RowLimit = appSettings.BatchSizeSyncLivescoreData
            };
            List<SeasonListResponse> seasons = await fetchSeasonService.FetchSeasonListAsync(param);
            if (seasons is not null && seasons.Exists(x => x.SportId == (short)SportType.Soccer))
            {
                var json = JsonConvert.SerializeObject(seasons.Where(x => x.SportId == (short)SportType.Soccer).ToList());
                var command = new ImportSeasonCommand
                {
                    SeasonListJson = json
                };
                await baseService.ExecuteNonQueryAsync(command);

                var leagueIds = seasons.Where(s => s.LeagueId != null && s.IsCurrent).Select(i => i.LeagueId).ToArray();
                await SyncLeagueSeason(leagueIds);
            }
        }

        private async Task SyncLeagueSeason(long?[] leagueIds)
        {
            if (leagueIds is not null && leagueIds.Any())
            {
                await syncLeagueService.SyncLeagueSeasonByIdsAsync(leagueIds);
            }
        }

        public async Task SyncStageListByLastUpdatedTimeAsync()
        {
            DateTime? lastUpdatedTime = await baseService.GetDataEntityAsync<DateTime?>(new GetStageLastUpdatedTimeCriteria());
            var param = new FetchSeasonListParams
            {
                LastUpdatedTime = lastUpdatedTime ?? appSettings.MinDateSyncLivescoreData,
                RowLimit = appSettings.BatchSizeSyncLivescoreData
            };
            List<StageListResponse> stages = await fetchSeasonService.FetchStageListAsync(param);
            if (stages is not null && stages.Exists(x => x.SportId == (short)SportType.Soccer))
            {
                var json = JsonConvert.SerializeObject(stages.Where(x => x.SportId == (short)SportType.Soccer).ToList());
                var command = new ImportStageCommand
                {
                    StageListJson = json
                };
                await baseService.ExecuteNonQueryAsync(command);
            }
        }

        public async Task SyncSeasonStandingsByLastUpdatedTimeAsync()
        {
            DateTime? lastUpdatedTime = await baseService.GetDataEntityAsync<DateTime?>(new GetSeasonStandingsLastUpdatedTimeCriteria());
            if (lastUpdatedTime.HasValue)
            {
                lastUpdatedTime = lastUpdatedTime.Value.AddMilliseconds(1);
            }
            else
            {
                lastUpdatedTime = appSettings.MinDateSyncLivescoreData;
            }

            var today = DateTimeUtilize.Now();
            
            const byte TotalDaysOfWeek = 7;
            var isLastUpdatedTime = true;
            //only get update standing data into 7 days per call api
            for (var date = lastUpdatedTime.Value.Date; date.Date <= today.Date; date = date.AddDays(TotalDaysOfWeek))
            {
                var fetchDate = isLastUpdatedTime ? (DateTime)lastUpdatedTime : date;
                isLastUpdatedTime = false;
                IList<UpdatedSeasonIdListResponse> updatedSeasonIds = await GetSeasonStandingsAsync(fetchDate);
                if (updatedSeasonIds is not null && updatedSeasonIds.Any())
                {
                    await GetStandingByIdThenImportAsync(updatedSeasonIds);
                }
            }
        }

        public async Task SyncSeasonTeamHonorByLastUpdatedTimeAsync()
        {
            DateTime? lastUpdatedTime = await baseService.GetDataEntityAsync<DateTime?>(new GetSeasonTeamHonorLastUpdatedTimeCriteria());
            var param = new FetchTeamHonorParams
            {
                LastUpdatedTime = lastUpdatedTime ?? appSettings.MinDateSyncLivescoreData,
                RowLimit = appSettings.BatchSizeSyncLivescoreData
            };
            List<TeamHonorResponse> honors = await fetchSeasonService.FetchTeamHonorAsync(param);
            if (honors is not null && honors.Any())
            {
                var command = new ImportSeasonTeamHonorCommand
                {
                    TeamHonorListJson = JsonConvert.SerializeObject(honors)
                };
                await baseService.ExecuteNonQueryAsync(command);
            }
        }

        public async Task SyncSeasonPlayerHonorByLastUpdatedTimeAsync()
        {
            DateTime? lastUpdatedTime = await baseService.GetDataEntityAsync<DateTime?>(new GetSeasonPlayerHonorLastUpdatedTimeCriteria());
            var param = new FetchTeamHonorParams
            {
                LastUpdatedTime = lastUpdatedTime ?? appSettings.MinDateSyncLivescoreData,
                RowLimit = appSettings.BatchSizeSyncLivescoreData
            };
            List<PlayerHonorResponse> honors = await fetchSeasonService.FetchPlayerHonorAsync(param);
            if (honors is not null && honors.Any())
            {
                var command = new ImportSeasonPlayerHonorCommand
                {
                    PlayerHonorListJson = JsonConvert.SerializeObject(honors)
                };
                await baseService.ExecuteNonQueryAsync(command);
            }
        }

        private async Task GetStandingByIdThenImportAsync(IList<UpdatedSeasonIdListResponse> updatedSeasonIds)
        {
            foreach (var item in updatedSeasonIds)
            {
                foreach (var seasonId in item.UpdatedSeasonIdList)
                {
                    var seasonStandings = await GetSeasonStandingsByIdAsync(seasonId);
                    if (seasonStandings is not null && seasonStandings.Any())
                    {
                        await ImportSeasonStandingsAsync(seasonId, seasonStandings);
                    }
                }
            }
        }

        protected internal virtual async Task<IList<UpdatedSeasonIdListResponse>> GetSeasonStandingsAsync(DateTime lastUpdatedTime)
        {
            var param = new FetchSeasonStandingsParams
            {
                SportId = (short)SportType.Soccer,
                LastUpdatedTime = lastUpdatedTime
            };
            return await fetchSeasonService.FetchSeasonStandingsAsync<UpdatedSeasonIdListResponse>(param);
        }

        protected internal virtual async Task<List<SeasonStandingsResponse>> GetSeasonStandingsByIdAsync(long seasonId)
        {
            var param = new FetchSeasonStandingsParams
            {
                SportId = (short)SportType.Soccer,
                SeasonId = seasonId
            };
            return await fetchSeasonService.FetchSeasonStandingsAsync<SeasonStandingsResponse>(param);
        }

        protected internal virtual async Task ImportSeasonStandingsAsync(long seasonId, IList<SeasonStandingsResponse> seasonStandings)
        {
            var records = new List<SeasonStandings>();
            foreach (var seasonStanding in seasonStandings)
            {
                foreach (var group in seasonStanding.Group)
                {
                    foreach (var rank in group.Rank)
                    {
                        var record = new SeasonStandings
                        {
                            StageId = seasonStanding.StageId ?? 0,
                            StageName = seasonStanding.StageName,
                            GroupName = group.GroupName,
                            Conference = group.Conference,
                            TeamId = rank.TeamId ?? 0,
                            Position = rank.Position,
                            PositionHome = rank.PositionHome,
                            PositionAway = rank.PositionAway,
                            Total = rank.Total,
                            TotalHome = rank.TotalHome,
                            TotalAway = rank.TotalAway,
                            Won = rank.Won,
                            WonHome = rank.WonHome,
                            WonAway = rank.WonAway,
                            Loss = rank.Loss,
                            LossHome = rank.LossHome,
                            LossAway = rank.LossAway,
                            Draw = rank.Draw,
                            DrawHome = rank.DrawHome,
                            DrawAway = rank.DrawAway,
                            Promotion = rank.Promotion,
                            Points = rank.Points,
                            PointsHome = rank.PointsHome,
                            PointsAway = rank.PointsAway,
                            DeductPoints = rank.DeductPoints,
                            Goals = rank.Goals,
                            GoalsHome = rank.GoalsHome,
                            GoalsAway = rank.GoalsAway,
                            GoalsAgainst = rank.GoalsAgainst,
                            GoalsAgainstHome = rank.GoalsAgainstHome,
                            GoalsAgainstAway = rank.GoalsAgainstAway,
                            GoalDiff = rank.GoalDiff,
                            GoalDiffHome = rank.GoalDiffHome,
                            GoalDiffAway = rank.GoalDiffAway,
                            UpdatedAt = group.UpdatedAt
                        };
                        records.Add(record);
                    }
                }
            }

            var command = new ImportSeasonStandingsCommand
            {
                SeasonId = seasonId,
                SeasonStandingsJson = JsonConvert.SerializeObject(records)
            };
            await baseService.ExecuteNonQueryAsync(command);
        }
    }
}