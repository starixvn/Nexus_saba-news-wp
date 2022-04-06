namespace SabaNews.Services
{
    using SabaNews.Core.Models;
    using SabaNews.Criteria.Logs;
    using SabaNews.Services.Interfaces;
    using System.Threading.Tasks;

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
