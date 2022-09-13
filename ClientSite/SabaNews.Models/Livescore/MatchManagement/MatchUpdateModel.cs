namespace SabaNews.Models.Livescore.MatchManagement
{
    using Microsoft.AspNetCore.Http;

    public class MatchUpdateModel
    {
        public long MatchId { get; set; }
        public long HomeTeamId { get; set; }

        public string HomeTeamNameVN { get; set; }

        public string HomeTeamIconVN { get; set; }

        public bool IsDeletedHomeIcon { get; set; }

        public IFormFile HomeIcon { get; set; }

        public long AwayTeamId { get; set; }

        public string AwayTeamNameVN { get; set; }

        public IFormFile AwayIcon { get; set; }

        public bool IsDeletedAwayIcon { get; set; }
    }
}
