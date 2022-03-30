namespace SabaNews.Livescore.SyncData.Criteria.Season
{
    using SabaNews.Core.Models;

    public class ImportSeasonPlayerHonorCommand : BaseNonQueryCommand
    {
        public string PlayerHonorListJson { get; set; }

        public override string GetSettingKey() => "Livescore_Sync_SeasonPlayerHonor_Import";

        public override bool IsValid() => !string.IsNullOrWhiteSpace(PlayerHonorListJson);
    }
}