namespace SabaNews.Criteria
{
    using Fanex.Data.Repository;

    public class GetPageDetailByCateIdCriteria : CriteriaBase
    {
        public int ID { get; set; }

        public override string GetSettingKey() => "get_page_detail_by_cateId";

        public override bool IsValid() => true;
    }
}
