namespace SabaNews.Livescore.Services
{
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Livescore.Models.Team;

    public interface ITeamService
    {
        Task<IList<TeamStage>> GetTeamByStage(long seasonId, long stageId);
    }
}