namespace SabaNews.Livescore.Services
{
    using System.Threading.Tasks;
    using SabaNews.Livescore.Services.Criteria.LiveMatch;
    using SabaNews.Livescore.Services.Interfaces;
    using SabaNews.Services;

    public class LiveMatchService : ILiveMatchService
    {
        private readonly IBaseService baseService;

        public LiveMatchService(IBaseService baseService)
        {
            this.baseService = baseService;
        }

        public async Task InsertLiveMatchToTemporaryTable(long[] matchIds)
        {
            var command = new InsertLiveMatchToTemporayTableCommand
            {
                MatchIds = matchIds is not null && matchIds.Length > 0 ? string.Join(",", matchIds) : string.Empty
            };

            await baseService.ExecuteNonQueryAsync(command);
        }
    }
}