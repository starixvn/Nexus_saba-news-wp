namespace SabaNews.Criteria.User
{
    using System;

    public class CreateUserMembershipEventDataCommand : BaseNonQueryCommand
    {
        public int UserId { get; set; }

        public int LuckyNumber { get; set; }

        public DateTime JoinDate { get; set; }

        public override string GetSettingKey() => "user_create_membership_event_data";

        public override bool IsValid() => UserId > 0;


    }
}
