namespace SabaNews.Livescore.Models.League
{
    using Microsoft.AspNetCore.Http;


    public class StandingUpdateModel
    {
        public long SeasonId { get; set; }
        public long StageId { get; set; }
        public long TeamId { get; set; }
        public string TeamNameVN { get; set; }
        public string TeamIconVN { get; set; }
        public int Position { get; set; }
        public bool IsDeletedIcon { get; set; }
        public bool IsUpdatePosition { get; set; }
        public IFormFile Icon { get; set; }
    }
}
