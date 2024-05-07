namespace SabaNews.Livescore.Services
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Core.Models;
    using SabaNews.Livescore.Models.Team;
    using SabaNews.Livescore.Services.Criteria.Team;

    public interface ITeamService
    {
        Task<IList<TeamStage>> GetTeamByStage(long seasonId, long stageId);

        Task<IList<PlayerCustom>> GetTeamPlayerById(long teamId);
        Task<CommandResult> CreatePlayerAsync(CreateTeamPlayerCommand cmd);
    }
}