namespace SabaNews.Livescore.SyncData.Criteria.League
{
    using SabaNews.Core.Models;

    public class ImportLeagueCommand : BaseNonQueryCommand
    {
        public string LeagueListJson { get; set; }

        public override string GetSettingKey() => "Livescore_Sync_League_Import";

        public override bool IsValid() => !string.IsNullOrWhiteSpace(LeagueListJson);
    }
}