namespace SabaNews.Core.Models
{
    using Newtonsoft.Json;
    using System.Collections.Generic;

    public class UserSession
    {
        public int Id { get; set; }

        public string Email { get; set; }

        public string Name { get; set; }

        public string Password { get; set; }

        public bool EmailConfirmed { get; set; }

        public string LoginType { get; set; }
        
        public string SavedPosts { get; set; }
        private List<int> ListSavedPosts { get; set; }

        public List<int> HandleSavePosts
        {
            get
            {
                var arrSavedPosts = new List<int>();
                if (SavedPosts == null) return arrSavedPosts;
                arrSavedPosts = JsonConvert.DeserializeObject<List<int>>(SavedPosts);
                return arrSavedPosts;
            }
            set 
            {
                this.ListSavedPosts = value;
            }
        }
            

    }
}
