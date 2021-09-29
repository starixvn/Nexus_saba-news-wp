namespace SabaNews.Services
{
    using SabaNews.Core.Models;
    using SabaNews.Criteria.Poll;
    using SabaNews.Models.Poll;
    using SabaNews.Services.Interfaces;
    using System.Collections.Generic;
    using System.Threading.Tasks;

    public class PollService : IPollService
    {
        private readonly IBaseService baseService;

        public PollService(IBaseService baseService)
        {
            this.baseService = baseService;
        }
        public async Task<IEnumerable<PollModel>> GetPollAsync(GetPollCriteria criteria)
        {
            return await baseService.GetDataAsync<PollModel>(criteria);
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
