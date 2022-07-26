namespace SabaNews.Livescore.Services
{
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Models.League;
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
            var command = new UpdateLeagueExtraCriteria
            {
                LeagueId = league.LeagueId,
                Priority = league.Priority,
                IsMajor = league.IsMajor
            };
            return await baseService.ExecuteNonQueryAsync(command);
        }

        public async Task<CommandResult> DeleteLeaguesExtraAsync(long leagueId)
        {
            var command = new DeleteLeagueExtraCriteria
            {
                LeagueId = leagueId
            };
            return await baseService.ExecuteNonQueryAsync(command);
        }

        public async Task<CommandResult> InsertLeaguesExtraAsync(long leagueId)
        {
            var command = new InsertLeagueExtraCriteria
            {
                LeagueId = leagueId,
                Priority = 0,
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
    }
}