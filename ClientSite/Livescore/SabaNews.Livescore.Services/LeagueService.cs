namespace SabaNews.Livescore.Services
{
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using SabaNews.Core.Constants;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Models.Match;
    using SabaNews.Livescore.Models.Team;
    using SabaNews.Livescore.Services.Criteria.League;

    public class LeagueService : ILeagueService
    {
        private readonly IBaseService baseService;
        private const int priorityAscending = 1;
        private const int priorityDescending = 2;
        private const int createdDateDescending = 3;
        private const int createdDateAscending = 4;
        public LeagueService(IBaseService baseService)
        {
            this.baseService = baseService;
        }

        public async Task<IList<League>> GetMajorLeagueAsync()
        {
            var criteria = new GetMajorLeagueCriteria();
            var leagues = await baseService.GetDataAsync<League>(criteria);
            return leagues.ToList();
        }

        public async Task<IList<TopScorers>> GetTop10ScorersAsync(GetTop10ScorersCriteria topScorersCriteria)
        {
            var top = await baseService.GetDataAsync<TopScorers>(topScorersCriteria);
            return top.ToList();
        }
        public async Task<IList<Player>> GetPlayerByIdTeamAsync(long teamId)
        {
            var criteria = new GetPlayerByTeamId() { TeamId = teamId };
            var players = await baseService.GetDataAsync<Player>(criteria);
            return players.ToList();
        }

        public async Task<IList<Team>> GetAllTeamAsync()
        {
            var criteria = new GetAllTeam() { LeagueId = WorldCup2022.LeagueIdWorldCup2022 };
            var teams = await baseService.GetDataAsync<Team>(criteria);
            return teams.ToList();
        }

        public async Task<IList<TopScorers>> GetAllTopScorersAsync()
        {
            var criteria = new GetAllTopScorersCriteria() { LeagueId = WorldCup2022.LeagueIdWorldCup2022, SeasonId = WorldCup2022.SeasonIdWorldCup2022 };
            var top = await baseService.GetDataAsync<TopScorers>(criteria);
            return top.ToList();
        }

        public async Task<IList<League>> GetLeagueByIdsAsync(GetLeagueByIdsCriteria criteria)
        {
            var leagues = await baseService.GetDataAsync<League>(criteria);
            return leagues.ToList();
        }

        public async Task<IList<League>> GetLeaguesAsync()
        {
            var leagues = await baseService.GetDataAsync<League>(new GetLeaguesCriteria());
            return leagues.ToList();
        }

        public async Task<IList<League>> GetAllLeaguesAsync()
        {
            var leagues = await baseService.GetDataAsync<League>(new GetAllLeaguesCriteria());
            return leagues.ToList();
        }

        public async Task<CommandResult> UpdateLeaguesExtraAsync(League league)
        {
            var leagueItem = await GetLeagueByIdsAsync(new GetLeagueByIdsCriteria { LeagueIds = ("[" + league.LeagueId.ToString() + "]") });
            var leaguesExtra = await GetLeaguesAsync();
            if(league.Priority > GetLeaguePriority(leaguesExtra) - 1)
            {
                league.Priority = GetLeaguePriority(leaguesExtra) - 1;
            }
            var command = new UpdateLeagueExtraCriteria
            {
                LeagueId = league.LeagueId,
                Priority = league.Priority,
                IsMajor = league.IsMajor
            };
            var commandUpdatePriority = new UpdateLeagueExtraPriorityCriteria
            {
                LeagueIdUpdate = league.LeagueId,
                Priority = league.Priority
            };
            if(league.Priority > leagueItem[0].Priority)
            {
                commandUpdatePriority.Priority = leagueItem[0].Priority;
                commandUpdatePriority.IsCheck = true;
                commandUpdatePriority.IsDelete = true;
                commandUpdatePriority.PriorityUpdate = league.Priority;
            }
            else
            {
                commandUpdatePriority.IsCheck = false;
                commandUpdatePriority.PriorityUpdate = 0;
                commandUpdatePriority.IsDelete = false;

            }
            var result = await baseService.ExecuteNonQueryAsync(command);
            _ = await baseService.ExecuteNonQueryAsync(commandUpdatePriority);
            return result;
        }

        public async Task<CommandResult> DeleteLeaguesExtraAsync(long leagueId)
        {
            var leagueDelete = await GetLeagueByIdsAsync(new GetLeagueByIdsCriteria { LeagueIds = ("[" + leagueId.ToString() + "]") });
            var command = new DeleteLeagueExtraCriteria
            {
                LeagueId = leagueId
            };
            var result = await baseService.ExecuteNonQueryAsync(command);
            var commandUpdatePriority = new UpdateLeagueExtraPriorityCriteria

            {
                LeagueIdUpdate = leagueDelete[0].LeagueId,
                Priority = leagueDelete[0].Priority,
                IsDelete = true,
                PriorityUpdate = 0,
                IsCheck = false
            };
            _ = await baseService.ExecuteNonQueryAsync(commandUpdatePriority);
            return result;
        }

        public async Task<CommandResult> InsertLeaguesExtraAsync(long leagueId)
        {
            var leagues = await GetLeaguesAsync();
            var command = new InsertLeagueExtraCriteria
            {
                LeagueId = leagueId,
                Priority = GetLeaguePriority(leagues),
                IsMajor = false,
                CreatedDate = DateTime.UtcNow
            };
            return await baseService.ExecuteNonQueryAsync(command);
        }

        public async Task<CommandResult> UpdateLeaguesAsync(UpdateLeagueCriteria updateLeague)
        {
            return await baseService.ExecuteNonQueryAsync(updateLeague);
        }

        public IList<League> SearchLeague(IList<League> leagues, string search, int isMajor)
        {
            if (search != null)
            {
                leagues = leagues.Where(x => (x.LeagueFullNameEN != null && x.LeagueFullNameEN.Contains(search))
                || (x.LeagueNameVN != null && x.LeagueNameVN.Contains(search))).ToList();
            }
            if (isMajor == 1)
            {
                leagues = leagues.Where(x => x.IsMajor).ToList();
            }
            if (isMajor == 0)
            {
                leagues = leagues.Where(x => !x.IsMajor).ToList();
            }
            return leagues;
        }

        public IList<League> SortLeague(IList<League> leagues, int typeSort)
        {
            if (typeSort == priorityAscending)
            {
                leagues = leagues.OrderBy(x => x.Priority).ToList();
            }
            if (typeSort == priorityDescending)
            {
                leagues = leagues.OrderByDescending(x => x.Priority).ToList();
            }
            if (typeSort == createdDateDescending)
            {
                leagues = leagues.OrderByDescending(x => x.CreatedDate).ToList();
            }
            if (typeSort == createdDateAscending)
            {
                leagues = leagues.OrderBy(x => x.CreatedDate).ToList();
            }
            return leagues;
        }

        public static int GetLeaguePriority(IList<League> leagues)
        {
            int priority = 0;
            foreach(var item in leagues)
            {
                if(item.Priority > priority)
                {
                    priority = item.Priority;
                }
            }
            return priority + 1;
        }

        public async Task<League> GetLeagueByIdAsync(long leagueId)
        {
            var leagues = await baseService.GetDataEntityAsync<League>(new GetLeagueByIdCriteria
            {
                LeagueId = leagueId
            });
            return leagues;
        }

        public async Task<IList<SeasonStandings>> GetLeagueSeasonStanding(GetLeagueSeasonStandingCriteria criteria)
        {
            var result = await baseService.GetDataAsync<SeasonStandings>(criteria);
            return result.ToList();
        }

        public async Task<CommandResult> InsertTopScorerAsync(TopScorers topScorers)
        {
            var criteria = new InsertTopScorers()
            {
                PlayerId = topScorers.PlayerId,
                SeasonId = WorldCup2022.SeasonIdWorldCup2022,
                LeagueId = WorldCup2022.LeagueIdWorldCup2022,
                TeamId = topScorers.TeamId,
                Goals = topScorers.Goals,
                Assists = topScorers.Assists,
                Top = topScorers.Top
            };

            var criteriaPriority = new UpdatePriorityTopScorers()
            {
                IsCheck = false,
                TopUpdate = -1,
                Top = topScorers.Top,
                RowNum = topScorers.RowNum
            };
            _ = await baseService.ExecuteNonQueryAsync(criteriaPriority);
            return await baseService.ExecuteNonQueryAsync(criteria);

        }

        public async Task<CommandResult> UpdateTopScorerAsync(TopScorers topScorers)
        {
            var playerUpdate = await GetTopScorersByRowNumAsync(topScorers.RowNum);
            var criteria = new UpdateTopScorers()
            {
                PlayerId = topScorers.PlayerId,
                SeasonId = WorldCup2022.SeasonIdWorldCup2022,
                LeagueId = WorldCup2022.LeagueIdWorldCup2022,
                TeamId = topScorers.TeamId,
                Goals = topScorers.Goals,
                Assists = topScorers.Assists,
                Top = topScorers.Top,
                RowNum = topScorers.RowNum
            };
            var criteriaPriority = new UpdatePriorityTopScorers()
            {
                Top = topScorers.Top,
                RowNum = topScorers.RowNum
            };
            if (topScorers.Top > playerUpdate[0].Top)
            {
                criteriaPriority.Top = playerUpdate[0].Top;
                criteriaPriority.IsCheck = true;
                criteriaPriority.TopUpdate = topScorers.Top;
            }
            else
            {
                criteriaPriority.IsCheck = false;
                criteriaPriority.TopUpdate = -99;
            }
            _ = await baseService.ExecuteNonQueryAsync(criteriaPriority);
            return await baseService.ExecuteNonQueryAsync(criteria);
        }

        public async Task<IList<TopScorers>> GetTopScorersByRowNumAsync(int rowNum)
        {
            var criteria = new GetTopScorersByRowNum() { RowNum = rowNum , LeagueId = WorldCup2022.LeagueIdWorldCup2022, SeasonId = WorldCup2022.SeasonIdWorldCup2022};
            var topScorers = await baseService.GetDataAsync<TopScorers>(criteria);
            return topScorers.ToList();
        }
    }
}