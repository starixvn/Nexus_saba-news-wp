namespace SabaNews.Livescore.SyncData.Criteria.Matches
{
    using SabaNews.Core.Models;

    internal class InsertTemporaryMatchCommand : BaseNonQueryCommand
    {
        public string MatchIds { get; set; }

        public bool IsSkipSyncFinishedMatches { get; set; }

        public override string GetSettingKey() => "Livescore_Match_Temporary_Insert";

        public override bool IsValid() => !string.IsNullOrWhiteSpace(MatchIds);
    }
}