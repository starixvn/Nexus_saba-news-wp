using Fanex.Data.Repository;

namespace SabaNews.Criteria.Posts
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
