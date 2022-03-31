namespace SabaNews.Livescore.SyncData.Criteria.Player
{
    using SabaNews.Core.Models;

    public class ImportPlayerCommand : BaseNonQueryCommand
    {
        public string PlayerListJson { get; set; }

        public override string GetSettingKey() => "Livescore_Sync_Player_Import";

        public override bool IsValid() => !string.IsNullOrWhiteSpace(PlayerListJson);
    }
}