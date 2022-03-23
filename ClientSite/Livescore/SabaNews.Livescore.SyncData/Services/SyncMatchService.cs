namespace SabaNews.Livescore.SyncData.Services
{
    using System.Linq;
    using System.Threading.Tasks;
    using Newtonsoft.Json;
    using SabaNews.Core.Models;
    using SabaNews.Livescore.Models.Synchronous;
    using SabaNews.Livescore.Provider.API.Services.Interfaces;
    using SabaNews.Livescore.SyncData.Criteria.Matches;
    using SabaNews.Livescore.SyncData.Services.Interfaces;
    using SabaNews.Services;

    public class SyncMatchService : ISyncMatchService
    {
        private readonly IBaseService baseService;
        private readonly IFetchMatchService fetchMatchService;

        public SyncMatchService(IBaseService baseService, IFetchMatchService fetchMatchService)
        {
            this.baseService = baseService;
            this.fetchMatchService = fetchMatchService;
        }

        public async Task<CommandResult> SyncMatchListAsync(string matchListJson)
        {
            var command = new InsertUpdateMatchCommand
            {
                MatchListJson = matchListJson
            };

            var result = await baseService.ExecuteNonQueryAsync(command);
            return result;
        }

        public async Task SyncMatchListAsync(SyncMatchParams syncMatchParams)
        {
            var matches = await fetchMatchService.FetchMatchListAsync(syncMatchParams);

            if (matches.Any())
            {
                var command = new InsertUpdateMatchCommand
                {
                    MatchListJson = JsonConvert.SerializeObject(matches)
                };

                await baseService.ExecuteNonQueryAsync(command);
            }
        }
    }
}