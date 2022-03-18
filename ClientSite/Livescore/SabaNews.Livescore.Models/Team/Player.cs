namespace SabaNews.Livescore.Models.Team
{
    using SabaNews.Livescore.Core;

    public class Player : BaseModel
    {
        public string Position { get; set; }

        public int JerseyNumner { get; set; }

        public int Order { get; set; }

        public bool IsFirst { get; set; }
    }
}