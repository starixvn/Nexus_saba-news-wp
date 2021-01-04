namespace SabaNews.Views.Shared.Components
{
    using Microsoft.AspNetCore.Mvc;
    using SabaNews.Core.Constants;
    using SabaNews.Models.ViewModel;
    using SabaNews.Services.Interfaces;
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
            var newPosts = await postService.GetPostsBySlugAsync(Constants.NewPostSlug, 0, 4);
            var currentMonth = DateTime.Now.Month;
            var currentYear = DateTime.Now.Year;
            var currentDay = DateTime.Now.Day;
            if (year != 0 && month != 0)
            {
                if (year != currentYear || month != currentMonth)
                {
                    currentDay = Constants.NumberDayNotInCurrentMonth;
                }
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