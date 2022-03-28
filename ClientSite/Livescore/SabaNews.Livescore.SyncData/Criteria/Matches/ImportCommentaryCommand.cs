namespace SabaNews.Livescore.SyncData.Criteria.Matches
{
    using SabaNews.Core.Models;

    internal class ImportCommentaryCommand : BaseNonQueryCommand
    {
        public long MatchId { get; set; }

        public string CommentaryJson { get; set; }

        public override string GetSettingKey() => "Livescore_Match_Commentary_Sync";

        public override bool IsValid() => MatchId > 0 && !string.IsNullOrWhiteSpace(CommentaryJson);
    }
}