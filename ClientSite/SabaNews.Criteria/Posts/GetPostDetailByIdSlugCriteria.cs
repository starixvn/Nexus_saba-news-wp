namespace SabaNews.Criteria.Posts
{
    using Fanex.Data.Repository;
    using SabaNews.Core.Constants;

    public class GetPostDetailByIdSlugCriteria : CriteriaBase
    {
        public long PostId { get; set; }
        public string Slug { get; set; }
        public string PostType { get; set; }

        public override string GetSettingKey() => "post_get_by_id_slug";

        public override bool IsValid() => PostId > 0 && !string.IsNullOrEmpty(Slug) && Slug.Length < Constants.StringLimitLength;
    }
}
