namespace SabaNews.Criteria.Posts
{
    using Fanex.Data.Repository;
    using SabaNews.Core.Constants;

    public class GetCateByIdCriteria : CriteriaBase
    {
        public string Slug { get; set; }
        public long CateId {get;set;}

        public override string GetSettingKey() => "category_get_by_id";

        public override bool IsValid() => !string.IsNullOrEmpty(Slug) && Slug.Length < Constants.StringLimitLength;
    }
}
