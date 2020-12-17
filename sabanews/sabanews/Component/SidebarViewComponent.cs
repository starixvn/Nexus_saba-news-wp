namespace sabanews.Component
{
    using Microsoft.AspNetCore.Mvc;
    using sabanews.Constants;
    using sabanews.Models;
    using sabanews.Services.Interfaces;
    using System;
    using System.Threading.Tasks;

    public class SidebarViewComponent : ViewComponent
    {
        private readonly IPostService postService;

        public SidebarViewComponent(IPostService postService)
        {
            this.postService = postService;
        }

        public async Task<IViewComponentResult> InvokeAsync(int year = 0, int month = 0)
        {
            var newPosts = await postService.GetPostsAsync(Constants.NewPostCategory, 0, 4);
            var currentMonth = Convert.ToInt32(DateTime.Now.Month.ToString());
            var currentYear = Convert.ToInt32(DateTime.Now.Year.ToString());
            var currentDay = Convert.ToInt32(DateTime.Now.Day.ToString());
            if (year != 0 && month != 0)
            {
                if (year != currentYear || month != currentMonth)
                    currentDay = 32;
                currentMonth = month;
                currentYear = year;
            }

            var firstDate = new DateTime(currentYear, currentMonth, 1);
            var firstDayOfWeek = ((int)firstDate.DayOfWeek == 0) ? 7 : (int)firstDate.DayOfWeek;
            int days = DateTime.DaysInMonth(currentYear, currentMonth);
            var Model = new SideBarViewModel
            {
                Posts = newPosts,
                Days = days,
                CurrentMonth = currentMonth,
                CurrentYear = currentYear,
                CurrentDay = currentDay,
                FirstDayOfWeek = firstDayOfWeek
            };

            return View(Model);
        }
    }
}