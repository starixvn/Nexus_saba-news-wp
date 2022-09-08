namespace SabaNews.Livescore.Services.Criteria.Match
{
    using SabaNews.Core.Models;

    public class UpdateMatchCommand : BaseNonQueryCommand
    {
        public long HomeTeamId { get; set; }

        public string HomeTeamNameVN { get; set; }

        public string HomeTeamIconVN { get; set; }

        public long AwayTeamId { get; set; }

        public string AwayTeamNameVN { get; set; }

        public string AwayTeamIconVN { get; set; }

        public override string GetSettingKey() => "Livescore_Match_Update";

        public override bool IsValid() => true;
    }
}
