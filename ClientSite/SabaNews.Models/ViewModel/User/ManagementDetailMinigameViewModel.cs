using SabaNews.Models.Event.WorldCup;
using System.Collections.Generic;

namespace SabaNews.Models.ViewModel.User
{
    public class ManagementDetailMinigameViewModel
    {
        public byte StageDetail { get; set; }
        public long UserId { get; set; }
        public long Totalpoint { get; set; }
        public long Rank { get; set; }
        public string Username { get; set; }
        public string Email { get; set; }
        public IList<UserMinigameModel> Votes { get; set; }
    }
}
