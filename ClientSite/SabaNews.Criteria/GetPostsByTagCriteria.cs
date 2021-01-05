namespace SabaNews.Criteria
{
    using Fanex.Data.Repository;

    public class GetPostsByTagCriteria   : CriteriaBase
    {
        public string _Tag { get; set; }

        public int _Start { get; set; }

        public int _End { get; set; }

        public int TotalItems { get; set; }

        public override string GetSettingKey() => "get_posts_by_tag";

        public override bool IsValid() => true;

    }
}
