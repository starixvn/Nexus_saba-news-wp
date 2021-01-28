using Fanex.Data.Repository;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace SabaNews.Criteria
{
    public class CmtGetLikeDataCriteria : CriteriaBase
    {
        public long CommentID { get; set; }

        public override string GetSettingKey() => "cmt_get_like_data_cmtId";

        public override bool IsValid() => true;
    }
}
