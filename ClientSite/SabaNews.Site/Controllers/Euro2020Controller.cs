using Microsoft.AspNetCore.Mvc;
using SabaNews.Core.Constants;
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
        public IActionResult Index()
        {
            ViewData["CategorySlug"] = Constants.Euro2020Slug;
            return View();
        }
    }
}
