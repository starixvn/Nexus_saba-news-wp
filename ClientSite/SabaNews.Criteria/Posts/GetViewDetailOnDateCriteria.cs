namespace SabaNews.Criteria.Posts
{
    using System;
    using Fanex.Data.Repository;

    public class GetViewDetailOnDateCriteria : CriteriaBase
    {
        public DateTime? CreatedDate { get; set; }

        public bool IsVideo { get; set; }

        public override string GetSettingKey() => "post_view_detail_get";

        public override bool IsValid() => CreatedDate.HasValue;
    }
}