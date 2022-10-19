namespace SabaNews.Services
{
    using SabaNews.Core.Services;
    using SabaNews.Criteria.Event.WorldCup;
    using SabaNews.Models.Event.WorldCup;
    using SabaNews.Services.Interfaces;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using System.Linq;

    public class WorldCupService : IWorldCupService
    {
        private readonly IBaseService baseService;

        public WorldCupService(IBaseService baseService)
        {
            this.baseService = baseService;
        }

        public async Task<IList<UserMinigameModel>> GetMinigameByUser(GetMinigameByUserCriteria criteria)
        {
            var result = await baseService.GetDataAsync<UserMinigameModel>(criteria);
            return result.ToList();
        }
    }
}
