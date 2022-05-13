namespace SabaNews.Livescore.Services.Interfaces
{
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;

    public interface ILiveMatchService
    {
        public Task InsertLiveMatchToTemporaryTable(long[] matchIds);

        public Task<IEnumerable<long>> GetNotExistedMatchAsyc();

        public Task<IEnumerable<long>> GetCurrentLiveMatchAsync();

        public Task UpdateLiveMatchStartTimeAsync(long matchId, DateTime? startTime, long startTimeStamp);
    }
}