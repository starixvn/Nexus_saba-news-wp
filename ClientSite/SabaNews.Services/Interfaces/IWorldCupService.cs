namespace SabaNews.Services.Interfaces
{
    using SabaNews.Criteria.Event.WorldCup;
    using SabaNews.Models.Event.WorldCup;
    using System.Collections.Generic;
    using System.Threading.Tasks;

    public interface IWorldCupService
    {
        Task<IList<UserMinigameModel>> GetMinigameByUser(GetMinigameByUserCriteria criteria);
    }
}
