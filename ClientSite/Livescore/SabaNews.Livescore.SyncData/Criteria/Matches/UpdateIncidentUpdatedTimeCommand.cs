namespace SabaNews.Livescore.SyncData.Criteria.Matches
{
    using SabaNews.Core.Models;
    using System;

    public class UpdateIncidentUpdatedTimeCommand : BaseNonQueryCommand
    {
        public DateTime UpdatedTime { get; set; }

        public override string GetSettingKey() => "Livescore_Match_Incident_UpdateTimeSync";

        public override bool IsValid() => true;
    }
}
