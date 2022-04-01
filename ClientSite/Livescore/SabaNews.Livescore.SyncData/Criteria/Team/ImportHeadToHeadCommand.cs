namespace SabaNews.Livescore.SyncData.Criteria.Team
{
    using SabaNews.Core.Models;

    internal class ImportHeadToHeadCommand : BaseNonQueryCommand
    {
        public string HeadToHeadJson { get; set; }

        public override string GetSettingKey() => "Livescore_Sync_HeadToHead";

        public override bool IsValid() => !string.IsNullOrWhiteSpace(HeadToHeadJson);
    }
}