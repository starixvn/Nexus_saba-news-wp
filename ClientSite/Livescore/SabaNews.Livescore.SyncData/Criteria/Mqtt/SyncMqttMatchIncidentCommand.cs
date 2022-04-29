namespace SabaNews.Livescore.SyncData.Criteria.Mqtt
{
    using SabaNews.Core.Models;

    internal class SyncMqttMatchIncidentCommand : BaseNonQueryCommand
    {
        public string IncindentJson { get; set; }

        public override string GetSettingKey() => "Livescore_SyncIncident_ReceivedFromMqtt";

        public override bool IsValid() => !string.IsNullOrWhiteSpace(IncindentJson);
    }
}