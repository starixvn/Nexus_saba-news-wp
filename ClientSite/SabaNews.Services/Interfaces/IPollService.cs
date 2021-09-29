namespace SabaNews.Services.Interfaces
{
    using SabaNews.Core.Models;
    using SabaNews.Criteria.Poll;
    using SabaNews.Models.Poll;
    using System.Collections.Generic;
    using System.Threading.Tasks;

    public interface IPollService
    {
        Task<IEnumerable<PollModel>> GetPollAsync(GetPollCriteria criteria);

        Task<CommandResult> VotePollSurveyAsync(int questId, int answerId, string userIp);
    }
}
