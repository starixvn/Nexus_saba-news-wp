namespace SabaNews.Services.Interfaces
{
    using System;
    using System.Collections.Generic;
    using System.Threading.Tasks;
    using SabaNews.Models.Posts;

    public interface IStatisticService
    {
        Task<IEnumerable<PostStatisticInfo>> GetPostViewStatistic(DateTime fromDate, DateTime toDate);

        Task<IEnumerable<ViewDetails>> GetPostViewDetails(DateTime createdDate, bool isVideo);
    }
}