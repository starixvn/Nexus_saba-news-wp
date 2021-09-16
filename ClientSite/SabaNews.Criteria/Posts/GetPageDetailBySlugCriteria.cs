namespace SabaNews.Criteria.Posts
{
    using Fanex.Data.Repository;

    public class GetPageDetailBySlugCriteria : CriteriaBase
    {
        public string Slug { get; set; }

        public override string GetSettingKey() => "get_page_detail_by_slug";

        public override bool IsValid() => !string.IsNullOrEmpty(Slug) && Slug.Length < 200;
    }
}