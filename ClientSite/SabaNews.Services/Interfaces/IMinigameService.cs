namespace SabaNews.Services.Interfaces
{
    using SabaNews.Core.Models;
    using SabaNews.Criteria.Event.Minigame;
    using SabaNews.Models.Event.Minigame;
    using System.Collections.Generic;
    using System.Threading.Tasks;

    public interface IMinigameService
    {
        Task<IList<MinigameModel>> GetLeagueMinigameByUserStageAsync(GetLeagueMinigameByUserStageCriteria criteria);

        Task<IList<MinigameModel>> GetMinigameByUserAsync(long userId, long leagueId, long seasonId);

        Task<IList<MinigameModel>> GetLeagueMinigameByStageAsync(GetLeagueMinigameByStageCriteria criteria);

        Task<MinigameMatchVotedModel> GetMinigameVoteMatchAsync(GetMinigameVotedMatchCriteria criteria);

        Task<CommandResult> UpdateMinigameVoteAsync(UpdateMinigameVoteCommand cmd);


    }
}
