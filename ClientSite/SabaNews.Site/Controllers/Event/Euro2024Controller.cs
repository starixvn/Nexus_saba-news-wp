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
using SabaNews.Criteria.Poll;
using SabaNews.Criteria.Posts;
using SabaNews.Livescore.Models.Match;
using SabaNews.Livescore.Services;
using SabaNews.Livescore.Services.Criteria.League;
using SabaNews.Models.Posts;
using SabaNews.Models.ViewModel;
using SabaNews.Models.ViewModel.Event.WorldCup;
using SabaNews.Models.ViewModel.Poll;
using SabaNews.Models.ViewModel.Posts;
using SabaNews.Services;
using SabaNews.Services.Interfaces;
using SabaNews.Site.Extensions;

namespace SabaNews.Site.Controllers.Event
{
    public class Euro2024Controller : Controller
    {
        private readonly IDataControlService dataControlService;
        private readonly IPostService postService;
        private readonly IConfiguration configuration;
        private readonly ISessionService sessionService;
        private readonly IUserService userService;
        private readonly ILeagueService leagueService;
        private readonly IOptionService optionService;
        private readonly IPollService pollService;
        private const string CategorySlugEuro = Euro2024.HomepageSlug;
        public Euro2024Controller(
            IDataControlService dataControlService,
            IPostService postService,
            ISessionService sessionService,
            IConfiguration configuration,
            IUserService userService,
            IPollService pollService,
            ILeagueService leagueService,
            IOptionService optionService
        )
        {
            this.dataControlService = dataControlService;
            this.postService = postService;
            this.configuration = configuration;
            this.sessionService = sessionService;
            this.userService = userService;
            this.pollService = pollService;
            this.leagueService = leagueService;
            this.optionService = optionService;
        }
        [Route("/euro-2024")]
        public async Task<IActionResult> Index ()
        {
            var model = await dataControlService.GetEuro2024PageViewModel();
            var header = new HeaderViewModel
            {
                HeadTitle = model.Category.Title,
                Title = Euro2024.TitleHomepage,
                Description = model.Category.Description,
                Slug = Euro2024.HomepageSlug
            };
            model.Header = header;
            return View("~/Views/Event/Euro2024/Index.cshtml", model);
        }
        [Route("/euro-2024/{slug}")]
        public async Task<IActionResult> PostDetailEuro (string slug)
        {
            var postDetail = await postService.GetPostDetailBySlugAsync(slug, Constants.postType);
            if (postDetail == null)
            {
                return View(Constants.EmptyBlogView);
            }
            var cateInfoWC = GetCateInfoEuro(postDetail.CatesName, postDetail.CategoriesSlug);

            var viewModel = await GetViewModelPostDetail(postDetail, cateInfoWC, slug);

            return View("~/Views/Event/Euro2024/PostDetail.cshtml", viewModel);
        }

        [Route("euro-2024/tin-moi-nhat")]
        public async Task<IActionResult> LastestNews ()
        {
            var cateSlugCriteria = new GetPostsByCateSlugCriteria { _CategorySlug = CategorySlugEuro, _Start = 0, _End = Constants.GetTenItems };
            var news = await postService.GetPostsByCateSlugAsync(cateSlugCriteria);

            var header = new HeaderViewModel
            {
                Title = Euro2024.TitleLastestNews,
                Description = Euro2024.TitleLastestNews,
                Slug = Euro2024.UrlLastestNewsSlug
            };

            var viewModel = new LastestNewsViewModel
            {
                Header = header,
                Posts = news,
                CategorySlug = CategorySlugEuro,
                Paging = new PagingModel
                {
                    PageSize = Constants.GetTenItems,
                    CurrentPage = Constants.FirstPage,
                    TotalPages = (int)Math.Ceiling(cateSlugCriteria.TotalItems / (double)Constants.GetTenItems)
                }
            };
            return View("~/Views/Event/Euro2024/LastestNewsEuro.cshtml" , viewModel);
        }


        [Route("euro-2024/bang-xep-hang")]
        public async Task<IActionResult> Standing()
        {
            var standingsCriteria = new GetLeagueSeasonStandingCriteria { LeagueId = Euro2024.LeagueId, SeasonId = Euro2024.SeasonId };
            IList<SeasonStandings> standings = await leagueService.GetLeagueSeasonStanding(standingsCriteria);
            var league = await leagueService.GetLeagueByIdAsync(Euro2024.LeagueId);
            var isKnockout = league.CurrentStageId > Euro2024.GroupStageId;
            var header = new HeaderViewModel
            {
                Title = Euro2024.TitleStanding,
                Description = Euro2024.TitleStanding,
                Slug = Euro2024.UrlStandingSlug
            };
            var viewModel = new StandingDetailViewModel
            {
                Header = header,
                StandingsViewModel = GroupStandingsByGroupName(standings),
                IsKnockoutStage = isKnockout
            };
            return View("~/Views/Event/Euro2024/DetailStanding.cshtml" , viewModel);
        }

        [Route("euro-2024/nhan-dinh")]
        public async Task<IActionResult> CommentaryNews()
        {
            var cateSlugCriteria = new GetPostsByCateSlugCriteria { _CategorySlug = Euro2024.CommentarySlug, _Start = 0, _End = Constants.GetTenItems };
            var news = await postService.GetPostsByCateSlugAsync(cateSlugCriteria);

            var header = new HeaderViewModel
            {
                Title = Euro2024.TitleCommentary,
                Description = Euro2024.TitleCommentary,
                Slug = Euro2024.UrlCommentarySlug
            };

            var viewModel = new LastestNewsViewModel
            {
                Header = header,
                Posts = news,
                CategorySlug = Euro2024.CommentarySlug,
                Paging = new PagingModel
                {
                    PageSize = Constants.GetTenItems,
                    CurrentPage = Constants.FirstPage,
                    TotalPages = (int)Math.Ceiling(cateSlugCriteria.TotalItems / (double)Constants.GetTenItems)
                }
            };
            return View("~/Views/Event/Euro2024/LastestNewsEuro.cshtml", viewModel);
        }

        [Route("euro-2024/video")]
        public async Task<IActionResult> Video()
        {
            var header = new HeaderViewModel
            {
                Title = Euro2024.TitleVideo,
                Description = Euro2024.TitleVideo,
                Slug = Euro2024.UrlVideoSlug
            };
            var cateSlugCriteria = new GetPostsByCateSlugCriteria { _CategorySlug = Euro2024.VideoSlug, _Start = 0, _End = Constants.GetTwelveItems + 1 };
            var videos = await postService.GetPostsByCateSlugAsync(cateSlugCriteria);
            var viewModel = new VideoViewModel
            {
                Header = header,
                Videos = videos,
                Paging = new PagingModel
                {
                    PageSize = Constants.GetTwelveItems,
                    CurrentPage = Constants.FirstPage,
                    TotalPages = (int)Math.Ceiling((cateSlugCriteria.TotalItems - 1) / (double)Constants.GetTwelveItems)
                }
            };
            return View("~/Views/Event/Euro2024/Video.cshtml", viewModel);
        }


        [HttpPost]
        public async Task<IActionResult> GetHomepageStandings()
        {
            var standings = await dataControlService.GetLeagueSeasonStandingAsync(Euro2024.LeagueId, Euro2024.SeasonId);
            var groupsStageStanding = standings.Where(standing => standing.StageId == Euro2024.GroupStageId).ToList();
            var groups = GroupStandingsByGroupName(groupsStageStanding);
            return PartialView("~/Views/Event/Euro2024/_HomepageStanding.cshtml", groups);
        }

        public List<StandingViewModel> GroupStandingsByGroupName(IList<SeasonStandings> seasonStandings)
        {
            var groupNames = GetGroupNames(seasonStandings);
            var standingsViewModels = new List<StandingViewModel>();

            for (int i = 0; i < groupNames.Count; i++)
            {
                standingsViewModels.Add(new StandingViewModel
                {
                    GroupName = groupNames[i],
                    Standings = seasonStandings.Where(x => x.GroupName == groupNames[i]).ToList()
                });
            }
            return standingsViewModels;
        }

        public List<string> GetGroupNames(IList<SeasonStandings> seasonStandings)
        {
            var groupNames = new List<string>();
            foreach (var item in seasonStandings.Where(standing => !groupNames.Exists(x => x == standing.GroupName)))
            {
                groupNames.Add(item.GroupName);
            }
            return groupNames;
        }

        private async Task<SingleViewModel> GetViewModelPostDetail(Post postWC, CateInfo cateInfoWC, string slug)
        {
            SavePost savePostWC = null;
            var isUserLogin = sessionService.User != null;
            if (isUserLogin)
            {
                var user = await userService.GetUserByIdAsync(sessionService.User.Id);
                savePostWC = user.HandleSavePosts.Find(val => val.PostID == postWC.ID);
            }
            var cateParentsWC = await postService.GetCateParentsAsync(cateInfoWC.Slug);
            cateInfoWC.Url ??= PostHelper.GetCategoryLink(cateParentsWC);
            var domain = configuration.GetValue<string>(Constants.BaseUrl);
            var viewModel = new SingleViewModel
            {
                SinglePost = postWC,
                IsLogin = isUserLogin,
                SavePost = savePostWC,
                AbsoluteUrl = $"{domain}{slug}",
                IsVideo = PostHelper.IsVideo(cateParentsWC),
                CateInfo = cateInfoWC
            };
            return viewModel;
        }

        private static CateInfo GetCateInfoEuro(string cateName, string cateSlugWC)
        {
            var slugWC = cateSlugWC;
            var cateNameWC = cateName;
            var cateEventWC = GetEventEuroInfo(cateSlugWC);
            if (cateEventWC != null)
            {
                return cateEventWC;
            }
            if (cateSlugWC.Contains(Constants.CollectionSlug) || (cateSlugWC.Contains(Constants.BasketballSlug)
                && !cateSlugWC.Contains(Constants.BasketballVideoSlug)) || cateSlugWC.Contains(Constants.TennisSlug))
            {
                return new CateInfo { Slug = Constants.OtherPostSlug, Name = Constants.OtherPosts };
            }

            var listCateSlugWC = cateSlugWC.Split(',');
            var listCateNameWC = cateName.Split(',');
            var subCateSlugWC = listCateSlugWC.Where(item => item.Trim() != Constants.FootballSlug && item.Trim() != Constants.VideoSlug).ToList();
            var subCateNameWC = listCateNameWC.Where(item => item.Trim() != Constants.Football && item.Trim() != Constants.Video).ToList();
            slugWC = subCateSlugWC.Count > 0 ? subCateSlugWC[0] : listCateSlugWC[0];
            cateNameWC = subCateNameWC.Count > 0 ? subCateNameWC[0] : listCateNameWC[0];
            return new CateInfo { Slug = slugWC.Trim(), Name = cateNameWC.Trim() };
        }

        private static CateInfo GetEventEuroInfo(string cateSlugWC)
        {
            var cateEventWC = PostHelper.GetEventCates();
            var listCateSlugWC = cateSlugWC.Split(',');
            foreach (var slug in listCateSlugWC)
            {
                var cate = cateEventWC.Find(item => item.Slug == slug.Trim());
                if (cate != null)
                {
                    return cate;
                }
            }
            return null;
        }

        [HttpPost]
        public async Task<IActionResult> GetFixture()
        {
            var data = await dataControlService.GetMatchBySeasonAsync(Euro2024.LeagueId, Euro2024.SeasonId);
            return Json(new { data.League, data.Matches });
        }

        [HttpPost]
        public async Task<IActionResult> GetTopScorers(bool more)
        {
            var topScorersCriteria = new GetTop10ScorersCriteria { LeagueId = Euro2024.LeagueId, SeasonId = Euro2024.SeasonId };
            var topScorers = await leagueService.GetTop10ScorersAsync(topScorersCriteria);
            ViewBag.More = more;
            return PartialView("~/Views/Event/WorldCup2022/_TopScorers.cshtml", topScorers);
        }


        [HttpPost]
        public async Task<IActionResult> GetPollData()
        {
            var pollOption = await optionService.GetOptionAsync(Euro2024.PollKey);
            if(pollOption == null)
            {
                return null;
            }
            PollViewModel result;
            var clientIP = Helper.GetClientIP(HttpContext);
            var criteria = new GetPollDetailCriteria
            {
                QuestId = int.Parse(pollOption.OptionValue),
                ThemeId = Euro2024.PollTheme,
                UserIp = clientIP
            };
            result = await pollService.GetPollDetailAsync(criteria);
            if(result.Question == null)
            {
                return Json(null);
            }
            return PartialView("~/Views/Event/Euro2024/_PollEuro2024.cshtml", result);
        }

        [HttpPost]
        public async Task<IActionResult> VotePoll(int questId, int answerId, int themeId)
        {
            var clientIP = Helper.GetClientIP(HttpContext);
            var result = await pollService.VotePollAsync(questId, answerId, clientIP);
            if (result.IsSuccessful)
            {
                return await GetPollData();
            }
            return Json(result);
        }
    }
}