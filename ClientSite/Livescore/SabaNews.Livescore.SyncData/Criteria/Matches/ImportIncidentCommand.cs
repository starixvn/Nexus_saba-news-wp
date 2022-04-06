namespace SabaNews.Livescore.SyncData.Criteria.Matches
{
    using SabaNews.Core.Models;

    internal class ImportIncidentCommand : BaseNonQueryCommand
    {
        public string IncidentJson { get; set; }

        public override string GetSettingKey() => "Livescore_Match_Incident_Sync";

        public override bool IsValid() => !string.IsNullOrWhiteSpace(IncidentJson);
    }
}