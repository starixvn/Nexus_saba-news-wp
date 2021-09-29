namespace SabaNews.Services.Interfaces
{
    using SabaNews.Core.Models;
    using SabaNews.Criteria.Poll;
    using SabaNews.Models.ViewModel.Poll;
    using System.Threading.Tasks;

    public interface IPollService
    {
        Task<PollViewModel> GetPollAsync(GetPollCriteria criteria);

        Task<CommandResult> VotePollSurveyAsync(int questId, int answerId, string userIp);
    }
}
