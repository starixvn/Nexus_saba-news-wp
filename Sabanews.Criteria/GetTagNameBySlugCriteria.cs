namespace sabanews.Criteria
{
    using Fanex.Data.Repository;

    public class GetTagNameBySlugCriteria : CriteriaBase
    {
        public string _Slug { get; set; }

        public override string GetSettingKey() => "get_tag_name_by_slug";

        public override bool IsValid() => true;
    }
}
