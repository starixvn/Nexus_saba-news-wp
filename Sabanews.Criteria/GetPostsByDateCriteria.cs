namespace sabanews.Criteria
{
    using Fanex.Data.Repository;

    public class GetPostsByDateCriteria : CriteriaBase
    {
        public string _Date { get; set; }

        public int _Start { get; set; }

        public int _End { get; set; }

        public override string GetSettingKey() => "get_posts_by_date";

        public override bool IsValid() => true;
    }
}
