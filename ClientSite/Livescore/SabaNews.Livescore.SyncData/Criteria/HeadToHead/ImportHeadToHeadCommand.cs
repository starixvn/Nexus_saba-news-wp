namespace SabaNews.Livescore.SyncData.Criteria.HeadToHead
{
    using SabaNews.Core.Models;

    internal class ImportHeadToHeadCommand : BaseNonQueryCommand
    {
        public string HeadToHeadJson { get; set; }

        public override string GetSettingKey() => "Livescore_SyncHeadToHead_Import";

        public override bool IsValid() => !string.IsNullOrWhiteSpace(HeadToHeadJson);
    }
}