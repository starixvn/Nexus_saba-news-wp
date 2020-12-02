namespace sabanews.Criteria
{
    using Fanex.Data.Repository;

    public class GetPostDetailCriteria : CriteriaBase
    {
        public int ID { get; set; }

        public override string GetSettingKey() => "get_post_detail";

        public override bool IsValid() => true;
    }
}
