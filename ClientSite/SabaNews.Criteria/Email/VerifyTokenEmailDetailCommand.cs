using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace SabaNews.Criteria.Email
{
    public class VerifyTokenEmailDetailCommand : BaseNonQueryCommand
    {
        public string TokenId { get; set; }
        public override string GetSettingKey() => "email_detail_verify_token";

        public override bool IsValid() => true;
    }
}
