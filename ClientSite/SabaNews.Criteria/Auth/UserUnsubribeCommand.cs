using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace SabaNews.Criteria.Auth
{
    public class UserUnsubribeCommand : BaseNonQueryCommand
    {
        public int UserId { get; set; }

        public override string GetSettingKey() => "user_unsubscribe";

        public override bool IsValid() => true;
    }
}
