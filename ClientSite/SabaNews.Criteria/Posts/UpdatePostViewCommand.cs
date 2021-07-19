namespace SabaNews.Criteria.Posts
{
    public class UpdatePostViewCommand : BaseNonQueryCommand
    {
        public int PostId { get; set; }

        public bool IsVideoPost { get; set; }

        public override string GetSettingKey() => "post_update_viewcount";

        public override bool IsValid() => PostId > 0;
    }
}