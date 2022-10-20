namespace SabaNews.Services.Interfaces
{
    using SabaNews.Core.Models;
    using SabaNews.Criteria.Event.WorldCup;
    using SabaNews.Models.Event.WorldCup;
    using System.Collections.Generic;
    using System.Threading.Tasks;

    public interface IWorldCupService
    {
        Task<IList<UserMinigameModel>> GetMinigameByUserAsync(GetMinigameByUserCriteria criteria);

        Task<CommandResult> UpdateMinigameVoteAsync(UpdateMinigameVoteCommand cmd);

        Task<MinigameMatchVote> GetMinigameVoteMatchAsync(GetMinigameVoteMatchCriteria criteria);
    }
}
