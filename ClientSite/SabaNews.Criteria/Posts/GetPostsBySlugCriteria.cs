namespace SabaNews.Criteria.Posts
{
    using Fanex.Data.Repository;

    public class GetPostsBySlugCriteria : CriteriaBase
    {
        public string _CategorySlug { get; set; }

        public int _Start { get; set; }

        public int _End { get; set; }

        public override string GetSettingKey() => "get_posts_by_slug";

        public override bool IsValid() => !string.IsNullOrEmpty(_CategorySlug) && _CategorySlug.Length < 200;
    }
}
