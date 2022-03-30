namespace SabaNews.Livescore.Provider.API.Models
{
    using System;

    public class AuthToken
    {
        public string Token { get; set; }

        public DateTime TokenExpired { get; set; }
    }
}