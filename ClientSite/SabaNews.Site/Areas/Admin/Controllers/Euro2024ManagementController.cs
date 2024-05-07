namespace SabaNews.Site.Areas.Admin.Controllers
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Constants;
    using SabaNews.Livescore.Services;
    using SabaNews.Livescore.Services.Criteria.Team;
    using SabaNews.Models.ViewModel;
    using SabaNews.Models.ViewModel.Poll;
    using SabaNews.Services.Interfaces;
    using System.Linq;
    using System.Threading.Tasks;

    public class Euro2024ManagementController : AdminController
    {
        private readonly IOptionService optionService;
        private readonly IPollService pollService;
        private readonly ITeamService teamService;
        private readonly IFileService fileService;

        public Euro2024ManagementController(IOptionService optionService, IPollService pollService, ITeamService teamService, IFileService fileService)
        {
            this.optionService = optionService;
            this.pollService = pollService;
            this.teamService = teamService;
            this.fileService = fileService;
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

        public async Task<ActionResult> Team()
        {
            var teams = await teamService.GetTeamByStage(Euro2024.SeasonId, Euro2024.GroupStageId);
            return View(teams);
        }
        

        public IActionResult TeamPlayer(long teamId)
        {
            return View(teamId);
        }

        [HttpPost]
        public async Task<ActionResult> GetPlayerGrid(long teamId)
        {
            var teams = await teamService.GetTeamPlayerById(teamId);
            return PartialView("_TeamPlayerGrid", teams);
        }
        [HttpPost]
        public async Task<ActionResult> CreatePlayer(CreatTeamModel param)
        {
            var icon = "";
            if (param.Icon != null)
            {
                var uploadResult = await fileService.UploadFileAsync(param.Icon, UploadDir.PlayerUploadFolder);
                icon = uploadResult.IsSuccessful ? uploadResult.AbsoluteUrl : icon;
            }
            var cmd = new CreateTeamPlayerCommand
            {
                TeamId = param.TeamId,
                PlayerName = param.PlayerName,
                PlayerFullName = param.PlayerFullName,
                Birthday = param.Birthday,
                ShirtNumber = param.ShirtNumber,
                Club = param.Club,
                Position = param.Position,
                Icon = icon
            };
            var result = await teamService.CreatePlayerAsync(cmd);
            return Json(result);
        }


        public async Task<ActionResult> UpdatePoll(long pollId)
        {
            var result = await optionService.UpdateOptionAsync(Euro2024.PollKey, pollId.ToString());
            return Json(result);
        }
    }
}
