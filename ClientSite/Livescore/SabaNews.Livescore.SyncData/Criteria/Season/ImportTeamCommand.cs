namespace SabaNews.Livescore.SyncData.Criteria.Season
{
    using SabaNews.Core.Models;

    public class ImportSeasonCommand : BaseNonQueryCommand
    {
        public string SeasonListJson { get; set; }

        public override string GetSettingKey() => "Livescore_Sync_Season_Import";

        public override bool IsValid() => !string.IsNullOrWhiteSpace(SeasonListJson);
    }
}