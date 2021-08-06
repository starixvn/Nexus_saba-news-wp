using Fanex.Data.Repository;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace SabaNews.Criteria.Email
{
    public class GetDetailEmailDetailCriteria : CriteriaBase
    {
        public int EmailId { get; set; }

        public int UserId { get; set; }


        public override string GetSettingKey() => "email_detail_get";

        public override bool IsValid() => true;
    }
}
