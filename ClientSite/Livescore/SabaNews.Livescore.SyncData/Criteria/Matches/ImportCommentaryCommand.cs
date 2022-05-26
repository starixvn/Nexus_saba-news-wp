namespace SabaNews.Livescore.SyncData.Criteria.Matches
{
    using SabaNews.Core.Models;

    public class ImportCommentaryCommand : BaseNonQueryCommand
    {
        public string CommentaryJson { get; set; }

        public override string GetSettingKey() => "Livescore_Match_Commentary_Sync";

        public override bool IsValid() => !string.IsNullOrWhiteSpace(CommentaryJson);
    }
}