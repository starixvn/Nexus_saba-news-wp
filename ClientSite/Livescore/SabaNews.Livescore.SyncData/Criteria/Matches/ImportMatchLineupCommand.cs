namespace SabaNews.Livescore.SyncData.Criteria.Matches
{
    using SabaNews.Core.Models;

    public class ImportMatchLineupCommand : BaseNonQueryCommand
    {
        public string MatchLineupJson { get; set; }

        public string LineupPlayerJson { get; set; }

        public override string GetSettingKey() => "Livescore_Match_Lineup_Sync";

        public override bool IsValid() => !string.IsNullOrWhiteSpace(MatchLineupJson) && !string.IsNullOrWhiteSpace(LineupPlayerJson);
    }
}