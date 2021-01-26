namespace SabaNews.Criteria
{
    using Fanex.Data.Repository;

    public class CmtGetChildrenCriteria : CriteriaBase
    {
        public int PostID { get; set; }

        public int CmtID { get; set; }

        public override string GetSettingKey() => "cmt_get_children";

        public override bool IsValid() => true;
    }
}
