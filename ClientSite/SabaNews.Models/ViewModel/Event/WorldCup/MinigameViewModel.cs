namespace SabaNews.Models.ViewModel.Event.WorldCup
{
    using SabaNews.Core.Models;
    using SabaNews.Models.ViewModel.User;
    using System.Collections.Generic;

    public class MinigameViewModel
    {
        public HeaderViewModel Header { get; set; }

        public IList<StandingViewModel> StandingsViewModel { get; set; }

        public UserSession User { get; set; }

        public bool IsLogin { get; set; }

        public bool IsAdmin { get; set; }

        public string BaseUrl { get; set; }
        public MembershipEventModel MinigameDetail { get; set; }
    }
}
