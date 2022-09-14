namespace SabaNews.Criteria.Posts
{
    using Fanex.Data.Repository;
    using System;

    public class GetPostlistCriteria : CriteriaBase
    {
        public DateTime _ToDate { get; set; }
        public int _Category { get; set; }

        public int _Subject { get; set; }

        public override string GetSettingKey() => "get_posts";

        public override bool IsValid() => true;
    }
}
