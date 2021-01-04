namespace SabaNews.Criteria
{
    using Fanex.Data.Repository;

    public class GetCateTitleCriteria : CriteriaBase
    {
        public int _Cate_id { get; set; }

        public override string GetSettingKey() => "get_category_title";

        public override bool IsValid() => true;
    }
}
