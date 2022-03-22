namespace SabaNews.Site.Controllers.Livescore
{
    using System;
    using System.Net.Http;
    using Microsoft.AspNetCore.Mvc;
    using Microsoft.AspNetCore.SignalR;
    using SabaNews.Hubs;

    public class SoccerController : Controller
    {
        private readonly IHubContext<LiveScoreHub, ILiveScoreClient> hubContext;
        private readonly IHttpClientFactory httpClientFactory;

        public SoccerController(
            IHubContext<LiveScoreHub, ILiveScoreClient> hubContext,
            IHttpClientFactory httpClientFactory)
        {
            this.hubContext = hubContext;
            this.httpClientFactory = httpClientFactory;
        }

        [Route("lich-thi-dau/bong-da/{eventDate?}")]
        public IActionResult Index(DateTime? eventDate)
        {
            var today = DateTime.Today;
            var minDate = new DateTime(2020, 1, 1);
            var maxDate = new DateTime(today.Year + 2, 1, 1).AddYears(1).AddDays(-1);
            eventDate = eventDate.HasValue ? eventDate.Value.Date : DateTime.Today;

            if(eventDate.Value < minDate.Date)
            {
                eventDate = minDate;
            }
            else
            {
                if(eventDate.Value > maxDate)
                {
                    eventDate = maxDate;
                }
            }

            return View("~/Views/LiveScore/Soccer/Index.cshtml", eventDate);
        }

        [Route("lich-thi-dau/bong-da/truc-tiep")]
        public IActionResult Live()
        {
            return View("~/Views/LiveScore/Soccer/Live.cshtml", DateTime.Today);
        }

        [Route("lich-thi-dau/bong-da/detail/{id:long}")]
        public IActionResult Detail(long id)
        {
            return View("~/Views/LiveScore/Soccer/Detail.cshtml", id);
        }
    }
}