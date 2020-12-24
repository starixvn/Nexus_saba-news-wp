namespace sabanews.Criteria
{
    using Fanex.Data.Repository;

    public class GetRelatedPostCriteria : CriteriaBase
    {
        public int _ID { get; set; }

        public int _Start { get; set; }

        public int _End { get; set; }

        public override string GetSettingKey() => "get_related_posts";

        public override bool IsValid() => true;
    }
}
