using Fanex.Data.Repository;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace SabaNews.Criteria.Email
{
    public class GetEmailDetailByEmailIdCriteria : CriteriaBase
    {
        public int EmailID { get; set; }

        public override string GetSettingKey() => "email_detail_get_by_emailId";

        public override bool IsValid() => true;
    }
}
