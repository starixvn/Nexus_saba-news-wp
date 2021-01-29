namespace SabaNews.Criteria
{
    using Fanex.Data.Repository;

    public class GetCateDetailCriteria : CriteriaBase
    {
        public string _Slug { get; set; }

        public override string GetSettingKey() => "get_category_detail";

        public override bool IsValid() => true;
    }
}
