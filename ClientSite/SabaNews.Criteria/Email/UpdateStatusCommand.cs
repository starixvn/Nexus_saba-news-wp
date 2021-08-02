using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace SabaNews.Criteria.Email
{
    public class UpdateStatusCommand : BaseNonQueryCommand
    {
        public int EmailId { get; set; }

        public int Status { get; set; }

        public override string GetSettingKey() => "email_update_status";

        public override bool IsValid() => true;
    }
}
