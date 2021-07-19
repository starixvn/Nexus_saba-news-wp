namespace SabaNews.Services.Interfaces
{
    using System;
    using System.Threading.Tasks;
    using SabaNews.Models.Posts;

    public interface IStatisticService
    {
        Task<PostViewStatistic> GetPostViewStatistic(DateTime fromDate, DateTime toDate);
    }
}