namespace SabaNews.Services
{
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Criteria.Event.Minigame;
    using SabaNews.Models.Event.Minigame;
    using SabaNews.Services.Interfaces;
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;

    public class MinigameService : IMinigameService
    {
        private readonly IBaseService baseService;

        public MinigameService(IBaseService baseService)
        {
            this.baseService = baseService;
        }

        public async Task<IList<MinigameModel>> GetLeagueMinigameByUserStageAsync(GetLeagueMinigameByUserStageCriteria criteria)
        {
            var result = await baseService.GetDataAsync<MinigameModel>(criteria);
            return result.ToList();
        }

        public async Task<IList<MinigameModel>> GetMinigameByUserAsync(long userId, long leagueId, long seasonId)
        {
            var criteria = new GetLeagueMinigameByUserCriteria { UserId = userId, LeagueId = leagueId, SeasonId = seasonId };
            var result = await baseService.GetDataAsync<MinigameModel>(criteria);
            return result.ToList();
        }

        public async Task<IList<MinigameModel>> GetLeagueMinigameByStageAsync(GetLeagueMinigameByStageCriteria criteria)
        {
            var result = await baseService.GetDataAsync<MinigameModel>(criteria);
            return result.ToList();
        }


        public async Task<MinigameMatchVotedModel> GetMinigameVoteMatchAsync(GetMinigameVotedMatchCriteria criteria)
        {
            var result = await baseService.GetDataEntityAsync<MinigameMatchVotedModel>(criteria);
            return result;
        }

        public async Task<CommandResult> UpdateMinigameVoteAsync(UpdateMinigameVoteCommand cmd)
        {
            return await baseService.ExecuteNonQueryAsync(cmd);
        }


    }
}
