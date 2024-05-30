namespace SabaNews.Criteria.Event.Minigame
{
    using Fanex.Data.Repository;

    public class GetLeagueMinigameAllUsersCriteria : CriteriaBase
    {
        public long LeagueId { get; set; }
        public long SeasonId { get; set;}

        public override string GetSettingKey() => "minigame_League_GetAllUsers";

        public override bool IsValid() =>  LeagueId > 0 && SeasonId > 0;
    }
}
