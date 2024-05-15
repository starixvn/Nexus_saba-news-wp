namespace SabaNews.Livescore.Services
{
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Models.Team;
    using SabaNews.Livescore.Services.Criteria.Team;
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;

    public class TeamService : ITeamService
    {
        private readonly IBaseService baseService;
        public TeamService(IBaseService baseService)
        {
            this.baseService = baseService;
        }

        public async Task<IList<TeamStage>> GetTeamByStage(long seasonId, long stageId)
        {
            var criteria = new GetTeamByStageCriteria { SeasonId = seasonId, StageId = stageId };
            var leagues = await baseService.GetDataAsync<TeamStage>(criteria);
            return leagues.ToList();
        }

        public async Task<IList<PlayerCustom>> GetTeamPlayerById(long teamId)
        {
            var criteria = new GetTeamPlayerByIdCriteria { TeamId = teamId };
            var players = await baseService.GetDataAsync<PlayerCustom>(criteria);
            return players.ToList();
        }

        public async Task<PlayerCustom> GetPlayerById(long playerId)
        {
            var criteria = new GetPlayerByIdCriteria { PlayerId = playerId };
            var players = await baseService.GetDataEntityAsync<PlayerCustom>(criteria);
            return players;
        }

        public async Task<CommandResult> CreatePlayerAsync(CreateTeamPlayerCommand cmd)
        {
            return await baseService.ExecuteNonQueryAsync(cmd);
        }

        public async Task<CommandResult> UpdatePlayerAsync(UpdateTeamPlayerCommand cmd)
        {
            return await baseService.ExecuteNonQueryAsync(cmd);
        }

        public async Task<CommandResult> DeletePlayerAsync(long playerId)
        {
            return await baseService.ExecuteNonQueryAsync(new DeleteTeamPlayerCommand { PlayerId = playerId });
        }
    }
}