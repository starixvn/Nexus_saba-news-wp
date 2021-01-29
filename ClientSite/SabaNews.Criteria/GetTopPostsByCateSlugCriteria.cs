using Fanex.Data.Repository;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace SabaNews.Criteria
{
    public class GetTopPostsByCateSlugCriteria : CriteriaBase
    {
        public string CateSlug { get; set; }

        public int Start { get; set; }

        public int End { get; set; }

        public override string GetSettingKey() => "get_top_posts_by_cate_slug";

        public override bool IsValid() => true;
    }
}
