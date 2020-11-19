namespace sabanews.Component
{
    using Microsoft.AspNetCore.Mvc;
    using sabanews.Models;
    using sabanews.Services;
    using System.Threading.Tasks;
    using sabanews.Constants;
    using System;

    public class SidebarViewComponent : ViewComponent
    {
        private readonly IPostService postService;

        public SidebarViewComponent(IPostService postService)
        {
            this.postService = postService;
        }

        public async Task<IViewComponentResult> InvokeAsync()
        {
            var model = await postService.GetAsync(new PostParam { Category = Constants.NewPostCategory, Start = 0, End = 4 });
            var currentMonth = Convert.ToInt32(DateTime.Now.Month.ToString()); 
            var currentYear = Convert.ToInt32(DateTime.Now.Year.ToString());
            var currentDay = Convert.ToInt32(DateTime.Now.Day.ToString());
            var firstDate = new DateTime(currentYear, currentMonth, 2);
            var firstDayOfWeek = ((int)firstDate.DayOfWeek == 0) ? 7 : (int)firstDate.DayOfWeek;
            int days = DateTime.DaysInMonth(currentYear, currentMonth);

            return View(model);
        }
    }
   
}
