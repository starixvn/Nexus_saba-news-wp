namespace SabaNews.Livescore.SyncData.Criteria.Season
{
    using SabaNews.Core.Models;

    public class ImportSeasonStandingsCommand : BaseNonQueryCommand
    {
        public long SeasonId { get; set; }
        public string SeasonStandingsJson { get; set; }

        public override string GetSettingKey() => "Livescore_Sync_SeasonStandings_Import";

        public override bool IsValid() => SeasonId > 0 && !string.IsNullOrWhiteSpace(SeasonStandingsJson);
    }
}