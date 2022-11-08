namespace SabaNews.Services
{
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Criteria.Event.WorldCup;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Models.Team;
    using SabaNews.Models.Event.WorldCup;
    using SabaNews.Services.Interfaces;
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;

    public class WorldCupService : IWorldCupService
    {
        private readonly IBaseService baseService;

        public WorldCupService(IBaseService baseService)
        {
            this.baseService = baseService;
        }

        public async Task<IList<UserMinigameModel>> GetMinigameByUserStageAsync(GetMinigameByUserStageCriteria criteria)
        {
            var result = await baseService.GetDataAsync<UserMinigameModel>(criteria);
            return result.ToList();
        }

        public async Task<IList<UserMinigameModel>> GetMinigameByUserAsync(long userId)
        {
            var result = await baseService.GetDataAsync<UserMinigameModel>(new GetMinigameByUserCriteria { UserId = userId });
            return result.ToList();
        }

        public async Task<CommandResult> UpdateMinigameVoteAsync(UpdateMinigameVoteCommand cmd)
        {
            return await baseService.ExecuteNonQueryAsync(cmd);
        }

        public async Task<MinigameMatchVote> GetMinigameVoteMatchAsync(GetMinigameVoteMatchCriteria criteria)
        {
            var result = await baseService.GetDataEntityAsync<MinigameMatchVote>(criteria);
            return result;
        }

        public async Task<IList<UserMinigameModel>> GetMinigameUsersAsync(GetMinigameUsersByStageCriteria criteria)
        {
            var result = await baseService.GetDataAsync<UserMinigameModel>(criteria);
            return result.ToList();
        }
    }
}
