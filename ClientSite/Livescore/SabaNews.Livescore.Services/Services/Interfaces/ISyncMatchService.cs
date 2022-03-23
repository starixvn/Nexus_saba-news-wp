namespace SabaNews.Livescore.Services.Interfaces
{
    using System;
    using System.Threading.Tasks;

    public interface ISyncMatchService
    {
        Task SyncMatchByDateRangeAsync(DateTime fromDate, DateTime toDate);

        Task SyncMatchByIdAsync(long matchId);
    }
}