namespace SabaNews.Livescore.Services
{
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

        public async Task<CommandResult> UpdateLeaguesExtraAsync(int leagueId, int priotiry, bool isMajor)
        {
            var command = new UpdateLeagueExtraCriteria
            {
                LeagueId = leagueId,
                Priority = priotiry,
                IsMajor = isMajor
            };
            return await baseService.ExecuteNonQueryAsync(command);
        }

        public async Task<CommandResult> DeleteLeaguesExtraAsync(int leagueId)
        {
            var command = new DeleteLeagueExtraCriteria
            {
                LeagueId = leagueId
            };
            return await baseService.ExecuteNonQueryAsync(command);
        }

        public async Task<CommandResult> InsertLeaguesExtraAsync(int leagueId)
        {
            var command = new InsertLeagueExtraCriteria
            {
                LeagueId = leagueId,
                Priority = 0,
                IsMajor = false
            };
            return await baseService.ExecuteNonQueryAsync(command);
        }
    }
}