namespace SabaNews.Livescore.SyncData.Criteria.HeadToHead
{
    using SabaNews.Core.Models;

    public class UpdateSyncedH2HMatchList : BaseNonQueryCommand
    {
        public long MatchId { get; set; }

        public override string GetSettingKey() => "Livescore_SyncHeadToHead_UpdateSyncedMatch";

        public override bool IsValid() => MatchId > 0;
    }
}