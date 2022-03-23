namespace SabaNews.Site.Controllers.Livescore
{
    using System;
    using Microsoft.AspNetCore.Mvc;

    public class SoccerController : Controller
    {
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

            return View("~/Views/Livescore/Soccer/Index.cshtml", eventDate);
        }

        [Route("lich-thi-dau/bong-da/truc-tiep")]
        public IActionResult Live()
        {
            return View("~/Views/Livescore/Soccer/Live.cshtml", DateTime.Today);
        }

        [Route("lich-thi-dau/bong-da/chi-tiet/{id:long}")]
        public IActionResult Detail(long id)
        {
            return View("~/Views/Livescore/Soccer/Detail.cshtml", id);
        }
    }
}