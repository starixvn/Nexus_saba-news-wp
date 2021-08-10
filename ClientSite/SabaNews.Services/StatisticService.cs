namespace SabaNews.Services
{
    using System;
    using System.Collections.Generic;
    using System.Linq;
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

        public async Task<IEnumerable<PostStatisticInfo>> GetPostViewStatistic(DateTime fromDate, DateTime toDate)
        {
            var criteria = new GetPostViewStatiscticCriteria
            {
                FromDate = fromDate,
                ToDate = toDate
            };

            var postStatistics = await baseService.GetDataAsync<PostStatisticInfo>(criteria);

            var results = FillBlankDateStatistic(fromDate, toDate, postStatistics);
            return results;
        }

        private static IEnumerable<PostStatisticInfo> FillBlankDateStatistic(DateTime fromDate, DateTime toDate, IEnumerable<PostStatisticInfo> postStatistics)
        {
            var result = new List<PostStatisticInfo>();
            double totalDays = (toDate - fromDate).TotalDays + 1;
            for (double i = 0; i < totalDays; i++)
            {
                if (postStatistics.Any(s => s.PostDate.Date == fromDate.Date))
                {
                    var item = postStatistics.FirstOrDefault(i => i.PostDate.Date == fromDate.Date);
                    result.Add(item);
                }
                else
                {
                    var filledItem = new PostStatisticInfo
                    {
                        PostDate = fromDate
                    };
                    result.Add(filledItem);
                }
                fromDate = fromDate.AddDays(1);
            }

            return result.OrderBy(x => x.PostDate);
        }
    }
}