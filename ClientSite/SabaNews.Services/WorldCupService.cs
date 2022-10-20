namespace SabaNews.Services
{
    using SabaNews.Core.Services;
    using SabaNews.Criteria.Event.WorldCup;
    using SabaNews.Models.Event.WorldCup;
    using SabaNews.Services.Interfaces;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using System.Linq;
    using SabaNews.Core.Models;
    using SabaNews.Criteria.Posts;
    using Fanex.Data.Repository;

    public class WorldCupService : IWorldCupService
    {
        private readonly IBaseService baseService;

        public WorldCupService(IBaseService baseService)
        {
            this.baseService = baseService;
        }

        public async Task<IList<UserMinigameModel>> GetMinigameByUserAsync(GetMinigameByUserCriteria criteria)
        {
            var result = await baseService.GetDataAsync<UserMinigameModel>(criteria);
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
    }
}
