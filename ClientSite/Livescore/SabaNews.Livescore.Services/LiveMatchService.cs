namespace SabaNews.Livescore.Services
{
    using System;
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;
    using SabaNews.Core.Services;
    using SabaNews.Livescore.Services.Criteria.LiveMatch;
    using SabaNews.Livescore.Services.Interfaces;

    public class LiveMatchService : ILiveMatchService
    {
        private readonly IBaseService baseService;

        public LiveMatchService(IBaseService baseService)
        {
            this.baseService = baseService;
        }

        public async Task<IEnumerable<long>> GetNotExistedMatchAsyc()
        {
            var criteria = new GetNotExistedMatchCriteria();
            return await baseService.GetDataAsync<long>(criteria);
        }

        public async Task InsertLiveMatchToTemporaryTable(long[] matchIds)
        {
            var command = new InsertLiveMatchToTemporayTableCommand
            {
                MatchIds = matchIds is not null && matchIds.Length > 0 ? string.Join(",", matchIds) : string.Empty
            };

            await baseService.ExecuteNonQueryAsync(command);
        }

        public async Task<IEnumerable<long>> GetCurrentLiveMatchAsync()
        {
            var critria = new GetCurrentLiveMatchCriteria();

            var result = await baseService.GetDataAsync<long>(critria);
            return result.ToArray();
        }

        public async Task UpdateLiveMatchStartTimeAsync(long matchId, DateTime? startTime, long startTimeStamp)
        {
            if (matchId > 0 && startTime.HasValue)
            {
                var command = new UpdateLiveMatchStartTimeCommand
                {
                    MatchId = matchId,
                    StartTime = startTime,
                    StartTimeStamp = startTimeStamp
                };
                await baseService.ExecuteNonQueryAsync(command);
            }
        }
    }
}