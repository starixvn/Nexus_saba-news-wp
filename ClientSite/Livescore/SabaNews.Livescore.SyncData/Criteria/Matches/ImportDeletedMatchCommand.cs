namespace SabaNews.Livescore.SyncData.Criteria.Matches
{
    using SabaNews.Core.Models;

    public class ImportDeletedMatchCommand : BaseNonQueryCommand
    {
        public string DeletedMatchJson { get; set; }

        public override string GetSettingKey() => "Livescore_SyncMatch_SyncDeteledMatch";

        public override bool IsValid()
             => !string.IsNullOrWhiteSpace(DeletedMatchJson);
    }
}