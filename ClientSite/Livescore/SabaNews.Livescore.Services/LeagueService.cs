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
    }
}