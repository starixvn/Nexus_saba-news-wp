namespace SabaNews.Criteria
{
    using Fanex.Data.Repository;

    public class GetPageDetailBySlugCriteria : CriteriaBase
    {
        public string Slug { get; set; }

        public override string GetSettingKey() => "get_page_detail_by_slug";

        public override bool IsValid() => true;
    }
}