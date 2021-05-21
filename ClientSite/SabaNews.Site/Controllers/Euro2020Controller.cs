using Microsoft.AspNetCore.Mvc;
using SabaNews.Core.Constants;
using SabaNews.Models;
using SabaNews.Services.Interfaces;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace SabaNews.Site.Controllers
{
    public class Euro2020Controller : Controller
    {
        private readonly IPostService postService;

        public Euro2020Controller(
            IPostService postService)
        {
            this.postService = postService;
        }
        [Route("/euro-2020/")]
        public async Task<IActionResult> Index()
        {
            ViewData["CategorySlug"] = Constants.Euro2020Slug;
            ViewData["Title"] = "Tổng quan";
            ViewData["CategorySlug"] = Constants.Euro2020Slug;
            return View();
        }

        [Route("/euro-2020/doi-tuyen-euro-2020")]
        public async Task<IActionResult> Team()
        {
            var postParam = new PostParam
            {
                Start = 0,
                End = Constants.Total,
                Slug = Constants.Euro2020TeamSlug
            };
            var postTemplate = await postService.GetPostsByCateSlugAsync(postParam);
            ViewData["CategorySlug"] = Constants.Euro2020Slug;
            ViewData["ActiveSubCate"] = Constants.Euro2020TeamSlug;
            ViewData["Title"] = Constants.Euro2020Team;
            return View(postTemplate.Posts);
        }

        [Route("/euro-2020/video-euro-2020")]
        public async Task<IActionResult> Video()
        {
            var postParam = new PostParam
            {
                Start = 0,
                End = Constants.Total,
                Slug = Constants.Euro2020VideoSlug
            };
            var postTemplate = await postService.GetPostsByCateSlugAsync(postParam);
            ViewData["CategorySlug"] = Constants.Euro2020Slug;
            ViewData["ActiveSubCate"] = Constants.Euro2020VideoSlug;
            ViewData["Title"] = Constants.Euro2020Video;
            return View(postTemplate.Posts);
        }

        [Route("/euro-2020/lich-thi-dau-euro-2020")]
        public async Task<IActionResult> Schedule()
        {
            ViewData["CategorySlug"] = Constants.Euro2020Slug;
            ViewData["ActiveSubCate"] = Constants.Euro2020ScheduleSlug;
            ViewData["Title"] = Constants.Euro2020Schedule;
            return View();
        }

        [Route("/euro-2020/bang-xep-hang-euro-2020")]
        public async Task<IActionResult> Ranking()
        {
            ViewData["CategorySlug"] = Constants.Euro2020Slug;
            ViewData["ActiveSubCate"] = Constants.Euro2020RankingSlug;
            ViewData["Title"] = Constants.Euro2020Ranking;
            return View();
        }
    }
}
