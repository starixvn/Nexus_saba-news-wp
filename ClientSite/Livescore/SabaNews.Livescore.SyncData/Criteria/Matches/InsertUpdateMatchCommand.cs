namespace SabaNews.Livescore.SyncData.Criteria.Matches
{
    using SabaNews.Core.Models;

    internal class InsertUpdateMatchCommand : BaseNonQueryCommand
    {
        public string MatchListJson { get; set; }

        public override string GetSettingKey() => "Livesocre_MatchInfo_Sync";

        public override bool IsValid() => !string.IsNullOrWhiteSpace(MatchListJson);
    }
}