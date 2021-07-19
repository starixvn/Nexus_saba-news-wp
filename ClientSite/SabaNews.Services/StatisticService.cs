namespace SabaNews.Services
{
    using System;
    using System.Threading.Tasks;
    using SabaNews.Criteria.Posts;
    using SabaNews.Models.Posts;
    using SabaNews.Services.Interfaces;

    public class StatisticService : IStatisticService
    {
        private readonly IBaseService baseService;

        public StatisticService(IBaseService baseService)
        {
            this.baseService = baseService;
        }

        public async Task<PostViewStatistic> GetPostViewStatistic(DateTime fromDate, DateTime toDate)
        {
            var criteria = new GetPostViewStatiscticCriteria
            {
                FromDate = fromDate,
                ToDate = toDate
            };

            return await baseService.GetDataEntityAsync<PostViewStatistic>(criteria);
        }
    }
}