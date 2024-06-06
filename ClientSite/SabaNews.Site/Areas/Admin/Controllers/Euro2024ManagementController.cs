namespace SabaNews.Site.Areas.Admin.Controllers
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Constants;
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
        private readonly IEuro2024Service euro2024Service;
        private readonly IFileService fileService;

        public Euro2024ManagementController(IOptionService optionService, IPollService pollService, IEuro2024Service euro2024Service, IFileService fileService)
        {
            this.optionService = optionService;
            this.pollService = pollService;
            this.euro2024Service = euro2024Service;
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
            var teams = await euro2024Service.GetTeamByStage(Euro2024.SeasonId, Euro2024.GroupStageId);
            return View(teams);
        }
        

        public IActionResult TeamPlayer(long teamId)
        {
            return View(teamId);
        }

        [HttpPost]
        public async Task<ActionResult> GetPlayerGrid(long teamId)
        {
            var teams = await euro2024Service.GetTeamPlayerById(teamId);
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
            var result = await euro2024Service.CreatePlayerAsync(cmd);
            return Json(result);
        }

        [HttpGet]
        public async Task<ActionResult> EditPlayer(long playerId)
        {
           var player = await euro2024Service.GetPlayerById(playerId);
           if(player == null)
           {
                return Json(null);
           }
            return PartialView("_EditTeamPlayerGrid", player);
        }

        [HttpPost]
        public async Task<ActionResult> EditPlayer(UpdateTeamModel param)
        {
            var player = await euro2024Service.GetPlayerById(param.PlayerId);
            var icon = param.IsDeletedIcon ? null : player.Icon;
            if (param.Icon != null)
            {
                var uploadResult = await fileService.UploadFileAsync(param.Icon, UploadDir.PlayerUploadFolder);
                fileService.DeleteFile(player.Icon, UploadDir.PlayerUploadFolder);
                icon = uploadResult.IsSuccessful ? uploadResult.AbsoluteUrl : icon;
            }
            var cmd = new UpdateTeamPlayerCommand
            {
                PlayerId = param.PlayerId,
                PlayerName = param.PlayerName,
                PlayerFullName = param.PlayerFullName,
                Birthday = param.Birthday,
                ShirtNumber = param.ShirtNumber,
                Club = param.Club,
                Position = param.Position,
                Icon = icon
            };
            var result = await euro2024Service.UpdatePlayerAsync(cmd);
            return Json(result);
        }

        [HttpPost]
        public async Task<ActionResult> DeletePlayer(long playerId)
        {
            var player = await euro2024Service.GetPlayerById(playerId);
            if(!string.IsNullOrEmpty(player.Icon))
            {
                fileService.DeleteFile(player.Icon, UploadDir.PlayerUploadFolder);
            }
            var result = await euro2024Service.DeletePlayerAsync(playerId);
            return Json(result);
        }


        public async Task<ActionResult> UpdatePoll(long pollId)
        {
            var result = await optionService.UpdateOptionAsync(Euro2024.PollKey, pollId.ToString());
            return Json(result);
        }
    }
}
