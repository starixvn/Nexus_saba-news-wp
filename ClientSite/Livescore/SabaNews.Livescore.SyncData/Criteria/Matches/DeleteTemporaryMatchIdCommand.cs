namespace SabaNews.Livescore.SyncData.Criteria.Matches
{
    using SabaNews.Core.Models;

    public class DeleteTemporaryMatchIdCommand : BaseNonQueryCommand
    {
        public string MatchIds { get; set; }

        public override string GetSettingKey() => "Livescore_Match_Temporary_Delete";

        public override bool IsValid() => !string.IsNullOrWhiteSpace(MatchIds);
    }
}