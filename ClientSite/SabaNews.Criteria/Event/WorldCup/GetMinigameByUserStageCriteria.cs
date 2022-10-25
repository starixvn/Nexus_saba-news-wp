namespace SabaNews.Criteria.Event.WorldCup
{
    using Fanex.Data.Repository;

    public class GetMinigameByUserStageCriteria : CriteriaBase
    {
        public long UserId { get; set; }

        public byte Stage { get; set; }

        public override string GetSettingKey() => "worldcup_minigame_getByUserStage";

        public override bool IsValid() => UserId > 0 && Stage <= 4 && Stage > 0;
    }
}
