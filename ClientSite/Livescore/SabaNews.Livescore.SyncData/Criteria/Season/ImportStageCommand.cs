namespace SabaNews.Livescore.SyncData.Criteria.Season
{
    using SabaNews.Core.Models;

    public class ImportStageCommand : BaseNonQueryCommand
    {
        public string StageListJson { get; set; }

        public override string GetSettingKey() => "Livescore_Sync_Stage_Import";

        public override bool IsValid() => !string.IsNullOrWhiteSpace(StageListJson);
    }
}