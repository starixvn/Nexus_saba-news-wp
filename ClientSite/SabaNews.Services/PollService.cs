namespace SabaNews.Services
{
    using SabaNews.Core.Models;
    using SabaNews.Criteria.Poll;
    using SabaNews.Models.Poll;
    using SabaNews.Models.ViewModel.Poll;
    using SabaNews.Services.Interfaces;
    using System.Threading.Tasks;
    using System.Linq;

    public class PollService : IPollService
    {
        private readonly IBaseService baseService;

        public PollService(IBaseService baseService)
        {
            this.baseService = baseService;
        }
        public async Task<PollViewModel> GetPollAsync(GetPollCriteria criteria)
        {
            var (polls, question) = await baseService.FetchMultipleAsync<PollModel, QuestionModel>(criteria);
            var result = new PollViewModel
            {
                Polls = polls,
                Question = question.FirstOrDefault(),
                IsUserVoted = criteria.IsVoted == 1,
                TotalVotes = polls.Sum(item => item.Vote)
            };
            return result;
        }

        public async Task<CommandResult> VotePollSurveyAsync(int questId, int answerId, string userIp)
        {
            var command = new VotePollSurveyCommand
            {
                QuestId = questId,
                AnswerId = answerId,
                UserIP = userIp
            };
            return await baseService.ExecuteNonQueryAsync(command);
        }
    }
}
