namespace SabaNews.Livescore.SyncData.Criteria.Matches
{
    using SabaNews.Core.Models;

    public class ImportMatchCommand : BaseNonQueryCommand
    {
        public string MatchListJson { get; set; }

        public bool SkipSyncUpdatedTime { get; set; }

        public override string GetSettingKey() => "Livescore_Match_Sync";

        public override bool IsValid() => !string.IsNullOrWhiteSpace(MatchListJson);
    }
}