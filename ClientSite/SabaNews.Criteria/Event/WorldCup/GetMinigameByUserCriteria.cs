namespace SabaNews.Criteria.Event.WorldCup
{
    using Fanex.Data.Repository;

    public class GetMinigameByUserCriteria : CriteriaBase
    {
        public long UserId { get; set; }
        public byte Stage { get; set; }

        public override string GetSettingKey() => "get_category_detail";

        public override bool IsValid() => UserId > 0 && Stage >= 1 && Stage <= 4 ;
    }
}
