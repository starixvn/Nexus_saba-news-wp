namespace SabaNews.Models.ViewModel.Event.Euro2024
{
    using System.Collections.Generic;


    public class StandingDetailViewModel
    {
        public HeaderViewModel Header { get; set; }
        public IList<StandingViewModel> StandingsViewModel { get; set; }
        public bool IsKnockoutStage { get; set; }
    }
}
