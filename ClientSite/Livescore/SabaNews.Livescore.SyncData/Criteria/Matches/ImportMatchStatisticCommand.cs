namespace SabaNews.Livescore.SyncData.Criteria.Matches
{
    using SabaNews.Core.Models;

    public class ImportMatchStatisticCommand : BaseNonQueryCommand
    {
        public string MatchScoreJson { get; set; }

        public string MatchDetailJson { get; set; }

        public override string GetSettingKey() => "Livescore_SyncMatch_StatisticImport";

        public override bool IsValid() => !string.IsNullOrWhiteSpace(MatchDetailJson) && !string.IsNullOrWhiteSpace(MatchScoreJson);
    }
}