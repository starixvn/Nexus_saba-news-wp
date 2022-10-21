namespace SabaNews.Criteria.Event.WorldCup
{
    using Fanex.Data.Repository;

    public class GetMinigameVotedMatchByUserStageCriteria : CriteriaBase
    {
        public long UserId { get; set; }
        public byte Stage { get; set; }

        public override string GetSettingKey() => "worldcup_minigame_getVotedMatchByUserStage";

        public override bool IsValid() => UserId > 0 && Stage > 0 && Stage <= 4;
    }
}
