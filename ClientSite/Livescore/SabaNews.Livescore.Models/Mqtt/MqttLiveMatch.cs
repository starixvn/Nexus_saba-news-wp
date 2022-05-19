namespace SabaNews.Livescore.Models.Mqtt
{
    public class MqttLiveMatch
    {
        private const int RegularScoreIndex = 0;
        private const int FirstHalfScoreIndex = 1;
        private const int OverTimeScoreIndex = 2;
        private const int HomePenaltyScoreIndex = 3;
        public long MatchId { get; set; }
        public int MatchStatus { get; set; }
        public long StartTimeStamp { get; set; }
        public string IfTimeKeeping { get; set; }
        public int[] HomeScore { get; set; }
        public int HomeRegularScore => HomeScore[RegularScoreIndex];
        public int HomeFirstHalfScore => HomeScore[FirstHalfScoreIndex];
        public int HomeOverTimeScore => HomeScore[OverTimeScoreIndex];
        public int HomePenaltyScore => HomeScore[HomePenaltyScoreIndex];
        public int[] AwayScore { get; set; }
        public int AwayRegularScore => AwayScore[RegularScoreIndex];
        public int AwayFirstHalfScore => AwayScore[FirstHalfScoreIndex];
        public int AwayOverTimeScore => AwayScore[OverTimeScoreIndex];
        public int AwayPenaltyScore => AwayScore[HomePenaltyScoreIndex];
        public int HomeRed { get; set; }
        public int HomeYellow { get; set; }
        public int HomeConner { get; set; }
        public int AwayRed { get; set; }
        public int AwayYellow { get; set; }
        public int AwayConner { get; set; }
        public int HomeMissGoal { get; set; }
        public int AwayMissGoal { get; set; }
        public int HomeControlRate { get; set; }
        public int AwayControlRate { get; set; }
        public int HomeOnGoal { get; set; }
        public int AwayOnGoal { get; set; }
        public int HomeDangerAttack { get; set; }
        public int AwayDangerAttack { get; set; }
        public int HomeAttack { get; set; }
        public int AwayAttack { get; set; }
    }
}