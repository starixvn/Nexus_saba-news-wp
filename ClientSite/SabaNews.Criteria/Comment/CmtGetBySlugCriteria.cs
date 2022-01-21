namespace SabaNews.Criteria.Comment
{
    using Fanex.Data.Repository;

    public class CmtGetBySlugCriteria : CriteriaBase
    {
        public string Slug { get; set; }

        public string PostType { get; set; }

        public string Search { get; set; }

        public string DateSort { get; set; }

        public int Start { get; set; }

        public int End { get; set; }

        public int TotalItems { get; set; }

        public override string GetSettingKey() => "cmt_get_by_slug";

        public override bool IsValid() => !string.IsNullOrEmpty(Slug);
    }
}
