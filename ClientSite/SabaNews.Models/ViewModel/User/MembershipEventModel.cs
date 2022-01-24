namespace SabaNews.Models.ViewModel.User
{
    using System;

    public class MembershipEventModel
    {
        public int ID { get; set; }

        public string Name { get; set; }

        public string Phone { get; set; }

        public DateTime JoinDate { get; set; }

        public int LuckyNumber { get; set; }
    }
}
