namespace SabaNews.Criteria.Posts
{
    using Fanex.Data.Repository;
    using SabaNews.Core.Constants;

    public class GetCateDetailCriteria : CriteriaBase
    {
        public string _Slug { get; set; }

        public override string GetSettingKey() => "get_category_detail";

        public override bool IsValid() => !string.IsNullOrEmpty(_Slug) && _Slug.Length < Constants.StringLimitLength;
    }
}
