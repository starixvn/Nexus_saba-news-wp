namespace SabaNews.Livescore.SyncData.Criteria.Matches
{
    using SabaNews.Core.Models;

    public class ImportMatchDetailCommand : BaseNonQueryCommand
    {
        public string MatchScoreJson { get; set; }

        public string MatchDetailJson { get; set; }

        public override string GetSettingKey() => "Livescore_Match_Detail_Sync";

        public override bool IsValid() => !string.IsNullOrWhiteSpace(MatchDetailJson) && !string.IsNullOrWhiteSpace(MatchScoreJson);
    }
}