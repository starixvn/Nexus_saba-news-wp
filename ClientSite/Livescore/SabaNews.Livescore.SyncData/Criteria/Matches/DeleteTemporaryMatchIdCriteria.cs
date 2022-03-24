namespace SabaNews.Livescore.SyncData.Criteria.Matches
{
    using SabaNews.Core.Models;

    public class DeleteTemporaryMatchIdCriteria : BaseNonQueryCommand
    {
        public string MatchIds { get; set; }

        public override string GetSettingKey() => "Livescore_Match_DeleteTemporaryMatchIds";

        public override bool IsValid() => !string.IsNullOrWhiteSpace(MatchIds);
    }
}