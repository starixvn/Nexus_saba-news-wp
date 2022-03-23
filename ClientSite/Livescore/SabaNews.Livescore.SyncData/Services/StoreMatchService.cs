namespace SabaNews.Livescore.SyncData.Services
{
    using System.Threading.Tasks;
    using SabaNews.Core.Models;
    using SabaNews.Livescore.SyncData.Criteria.Matches;
    using SabaNews.Livescore.SyncData.Services.Interfaces;
    using SabaNews.Services;

    public class StoreMatchService : IStoreMatchService
    {
        private readonly IBaseService baseService;

        public StoreMatchService(IBaseService baseService)
        {
            this.baseService = baseService;
        }

        public async Task<CommandResult> StoreMatchListAsync(string matchListJson)
        {
            var command = new InsertUpdateMatchCommand
            {
                MatchListJson = matchListJson
            };

            var result = await baseService.ExecuteNonQueryAsync(command);
            return result;
        }
    }
}