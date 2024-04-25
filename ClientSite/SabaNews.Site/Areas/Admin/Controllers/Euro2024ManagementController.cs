namespace SabaNews.Site.Areas.Admin.Controllers
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Constants;
    using SabaNews.Models.ViewModel.Poll;
    using SabaNews.Services.Interfaces;
    using System.Linq;
    using System.Threading.Tasks;

    public class Euro2024ManagementController : AdminController
    {
        private readonly IOptionService optionService;
        private readonly IPollService pollService;

        public Euro2024ManagementController(IOptionService optionService, IPollService pollService)
        {
            this.optionService = optionService;
            this.pollService = pollService;
        }
        public async Task<ActionResult> Poll()
        {
            var polls = await pollService.GetAllPollAsync();
            var currentQuestion = await optionService.GetOptionAsync(Euro2024.PollKey);
            var currentQuestionName = "-------";
            var questId = 0;
            if(currentQuestion != null)
            {
                questId = int.Parse(currentQuestion.OptionValue);
                currentQuestionName = polls.First(item => item.Id == questId).Question;
            }
            var viewModel = new PollAdminViewModel
            {
                Polls = polls,
                SelectedQuestId = questId,
                SelectedQuest = currentQuestionName
            };
            return View(viewModel);
        }

        public async Task<ActionResult> UpdatePoll(long pollId)
        {
            var result = await optionService.UpdateOptionAsync(Euro2024.PollKey, pollId.ToString());
            return Json(result);
        }
    }
}
