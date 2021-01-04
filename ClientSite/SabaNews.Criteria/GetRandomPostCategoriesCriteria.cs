namespace SabaNews.Criteria
{
    using Fanex.Data.Repository;

    public class GetRandomPostCategoriesCriteria : CriteriaBase
    {
        public string _Categories { get; set; }

        public int _ID { get; set; }

        public override string GetSettingKey() => "get_random_post_categories";

        public override bool IsValid() => true;
    }
}
