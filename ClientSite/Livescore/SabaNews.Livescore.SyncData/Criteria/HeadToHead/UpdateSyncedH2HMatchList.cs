namespace SabaNews.Livescore.SyncData.Criteria.HeadToHead
{
    using SabaNews.Core.Models;

    internal class UpdateSyncedH2HMatchList : BaseNonQueryCommand
    {
        public string MatchIds { get; set; }

        public override string GetSettingKey() => "Livescore_SyncHeadToHead_UpdateSyncedMatches";

        public override bool IsValid() => !string.IsNullOrWhiteSpace(MatchIds);
    }
}