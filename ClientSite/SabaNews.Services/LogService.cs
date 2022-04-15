namespace SabaNews.Services
{
    using System.Threading.Tasks;
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Criteria.Logs;
    using SabaNews.Services.Interfaces;

    public class LogService : ILogService
    {
        private readonly IBaseService baseService;

        public LogService(IBaseService baseService)
        {
            this.baseService = baseService;
        }

        public async Task<CommandResult> WriteLog(WriteLogCommand cmd)
        {
            return await baseService.ExecuteNonQueryAsync(cmd);
        }
    }
}