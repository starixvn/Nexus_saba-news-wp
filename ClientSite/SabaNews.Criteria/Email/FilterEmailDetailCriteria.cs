using Fanex.Data.Repository;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace SabaNews.Criteria.Email
{
    public class FilterEmailDetailCriteria : CriteriaBase
    {
        public int EmailID { get; set; }

        public string Search { get; set; } = "";

        public int IsClicked { get; set; } = -1;

        public int Status { get; set; } = -1;

        public int Start { get; set; }

        public int End { get; set; }

        public int TotalItems { get; set; }

        public override string GetSettingKey() => "email_detail_filter";

        public override bool IsValid() => true;
    }
}
