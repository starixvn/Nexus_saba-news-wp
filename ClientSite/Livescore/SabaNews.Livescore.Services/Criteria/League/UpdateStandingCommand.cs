namespace SabaNews.Livescore.Services.Criteria.League
{
    using SabaNews.Core.Models;

    public class UpdateStandingCommand : BaseNonQueryCommand
    {
        public long SeasonId { get; set; }
        public long StageId { get; set; }
        public long TeamId { get; set; }
        public string TeamNameVN { get; set; }
        public string IconVN { get; set; }
        public override string GetSettingKey() => "Livescore_Standing_Update";
        public override bool IsValid() => SeasonId > 0;
    }
}
