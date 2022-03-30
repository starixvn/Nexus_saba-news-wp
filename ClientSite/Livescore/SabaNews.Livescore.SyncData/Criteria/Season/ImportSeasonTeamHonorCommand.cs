namespace SabaNews.Livescore.SyncData.Criteria.Season
{
    using SabaNews.Core.Models;

    public class ImportSeasonTeamHonorCommand : BaseNonQueryCommand
    {
        public string TeamHonorListJson { get; set; }

        public override string GetSettingKey() => "Livescore_Sync_SeasonTeamHonor_Import";

        public override bool IsValid() => !string.IsNullOrWhiteSpace(TeamHonorListJson);
    }
}