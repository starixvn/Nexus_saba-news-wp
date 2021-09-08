namespace SabaNews.Criteria.Posts
{
    using Fanex.Data.Repository;

    public class GetPostsByCateSlugCriteria : CriteriaBase
    {
        public string _CategorySlug { get; set; }
        public int _Start { get; set; }
        public int _End { get; set; }
        public int TotalItems { get; set; }

        public override string GetSettingKey() => "get_posts_by_cate_slug";

        public override bool IsValid() => true;
    }
}
