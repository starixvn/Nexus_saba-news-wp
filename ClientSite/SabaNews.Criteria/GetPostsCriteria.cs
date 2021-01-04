namespace SabaNews.Criteria
{
    using Fanex.Data.Repository;

    public class GetPostsCriteria : CriteriaBase
    {
        public int _Category { get; set; }

        public int _Start { get; set; }

        public int _End { get; set; }

        public override string GetSettingKey() => "get_posts";

        public override bool IsValid() => true;
    }
}