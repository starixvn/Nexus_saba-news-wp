namespace SabaNews.Criteria.Poll
{
    using Fanex.Data.Repository;

    public class GetPollCriteria : CriteriaBase
    {
        public int QuestId { get; set; }

        public int ThemeId { get; set; }

        public string UserIp { get; set; }

        public int IsVoted { get; set; }

        public override string GetSettingKey() => "poll_get";

        public override bool IsValid() => QuestId > 0 && ThemeId > 0;


    }
}
