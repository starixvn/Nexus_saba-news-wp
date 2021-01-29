namespace SabaNews.Criteria
{
    using Fanex.Data.Repository;

    public class SearchPostsCriteria : CriteriaBase
    {
        public string _Keyword { get; set; }

        public int _Start { get; set; }

        public int _End { get; set; }

        public int TotalItems { get; set; }

        public override string GetSettingKey() => "search_posts";

        public override bool IsValid() => true;
    }
}
