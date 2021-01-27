namespace SabaNews.Core.Repositories
{
    using Fanex.Data;
    using Fanex.Data.Repository;
    using SabaNews.Core.Models;
    using System;
    using System.Collections;
    using System.Threading.Tasks;

    public interface IBaseRepository
    {
        Task<CommandResult> ExecuteNonQueryAsync(ICriteria criteria);
    }

    public class BaseRepository : IBaseRepository
    {
        public async Task<CommandResult> ExecuteNonQueryAsync(ICriteria criteria)
        {
            using (var db = ObjectDbFactory.CreateInstance(criteria.GetSettingKey()))
            {
                await db.ExecuteNonQueryAsync(criteria);
                CommandResult commandResult = GetCommandResult(db.GetOutputParams());

                return commandResult;
            }
        }

        private static CommandResult GetCommandResult(Hashtable hash)
        {
            var errCode = hash["errCode"];
            var errMsg = hash["errMsg"];

            return new CommandResult
            {
                Code = (errCode is DBNull) ? 0 : Convert.ToInt32(errCode),
                Message = (errMsg is DBNull) ? string.Empty : Convert.ToString(errMsg)
            };
        }
    }
}
