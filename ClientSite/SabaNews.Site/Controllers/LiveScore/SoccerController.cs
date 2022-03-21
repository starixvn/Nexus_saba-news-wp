namespace SabaNews.Site.Controllers.LiveScore
{
    using System;
    using System.Net.Http;
    using System.Threading.Tasks;
    using Microsoft.AspNetCore.Mvc;
    using Microsoft.AspNetCore.SignalR;
    using Newtonsoft.Json;
    using SabaNews.Hubs;
    using SabaNews.Models.LiveScore;

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

        [HttpPost]
        public async Task<IActionResult> GetSoccerMatches()
        {
            var httpClient = httpClientFactory.CreateClient();
            httpClient.DefaultRequestHeaders.Add("Key", "EDA8F37E5E644977BB5358217A605757");
            var url = new Uri("https://api-dot-sabasport-com.df.r.appspot.com/api/1.0/Match/Soccer/Today");
            HttpResponseMessage response = await httpClient.PostAsync(url, null);
            response.EnsureSuccessStatusCode();
            var responseData = await response.Content.ReadAsStringAsync();
            var responseResult = JsonConvert.DeserializeObject<SoccerMatchQueryResponseResult>(responseData);
            ////await hubContext.Clients.All.UpdateLiveSoccer(responseResult.Result.Match);

            return Json(responseResult.Result.Match);
        }

        [HttpPost]
        public async Task<IActionResult> GetLiveSoccerMatches()
        {
            var httpClient = httpClientFactory.CreateClient();
            httpClient.DefaultRequestHeaders.Add("Key", "EDA8F37E5E644977BB5358217A605757");
            var url = new Uri("https://api-dot-sabasport-com.df.r.appspot.com/api/1.0/Match/Live");
            HttpResponseMessage response = await httpClient.PostAsync(url, null);
            response.EnsureSuccessStatusCode();
            var responseData = await response.Content.ReadAsStringAsync();
            var responseResult = JsonConvert.DeserializeObject<LiveMatchQueryResponseResult>(responseData);
            await hubContext.Clients.All.UpdateLiveSoccer(responseResult.Result.SoccerMatch);

            return Ok();
        }
    }
}