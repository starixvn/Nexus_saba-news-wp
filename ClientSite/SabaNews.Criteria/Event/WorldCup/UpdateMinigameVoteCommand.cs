namespace SabaNews.Criteria.Event.WorldCup
{
    public class UpdateMinigameVoteCommand : BaseNonQueryCommand
    {
        public long UserId { get; set; }

        public long MatchId { get; set; }

        public byte Stage { get; set; }

        public long TeamId { get; set; }

        public override string GetSettingKey() => "worldcup_minigame_updateVote";

        public override bool IsValid() => UserId > 0 && MatchId > 0 && Stage <= 4 && Stage > 0;
    }
}
