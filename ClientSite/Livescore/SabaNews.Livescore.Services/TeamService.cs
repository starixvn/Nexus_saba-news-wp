namespace SabaNews.Livescore.Services
{
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
    }
}