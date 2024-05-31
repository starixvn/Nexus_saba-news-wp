using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using Microsoft.Extensions.Configuration;
using SabaNews.Core.Constants;
using SabaNews.Core.Helper;
using SabaNews.Core.Models;
using SabaNews.Core.Services;
using SabaNews.Criteria.Event.Minigame;
using SabaNews.Criteria.Poll;
using SabaNews.Criteria.Posts;
using SabaNews.Livescore.Models.Match;
using SabaNews.Livescore.Services;
using SabaNews.Livescore.Services.Criteria.League;
using SabaNews.Livescore.Services.Criteria.Match;
using SabaNews.Models.Posts;
using SabaNews.Models.ViewModel;
using SabaNews.Models.ViewModel.Event.Euro2024;
using SabaNews.Models.ViewModel.Event.WorldCup;
using SabaNews.Models.ViewModel.Poll;
using SabaNews.Models.ViewModel.Posts;
using SabaNews.Services;
using SabaNews.Services.Interfaces;
using SabaNews.Site.Extensions;

namespace SabaNews.Site.Controllers.Event
{
    public class MinigameController : Controller
    {
        private readonly IDataControlService dataControlService;
        private readonly IPostService postService;
        private readonly IConfiguration configuration;
        private readonly ISessionService sessionService;
        private readonly IMinigameService minigameService;
        private const string EuroController = "Euro2024";
        private const string CategorySlugEuro = Euro2024.HomepageSlug;
        public MinigameController(
            IDataControlService dataControlService,
            IPostService postService,
            ISessionService sessionService,
            IConfiguration configuration,
            IMinigameService minigameService
        )
        {
            this.dataControlService = dataControlService;
            this.postService = postService;
            this.configuration = configuration;
            this.sessionService = sessionService;
            this.minigameService = minigameService;
        }
    }
}