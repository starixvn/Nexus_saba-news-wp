namespace SabaNews.Livescore.SyncData.Criteria.Matches
{
    using Fanex.Data.Repository;

    public class GetIncidentLastUpdatedTimeCriteria : CriteriaBase
    {
        public override string GetSettingKey() => "Livescore_Match_Incident_GetLastUpdatedTime";

        public override bool IsValid() => true;
    }
}
