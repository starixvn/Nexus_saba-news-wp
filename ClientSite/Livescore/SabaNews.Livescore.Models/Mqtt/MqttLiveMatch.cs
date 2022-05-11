namespace SabaNews.Livescore.Models.Mqtt
{
    public class MqttLiveMatch
    {
        public long MatchId { get; set; }
        public int MatchStatus { get; set; }
        public long StartTimeStamp { get; set; }
        public string IfTimeKeeping { get; set; }
        public int[] HomeScore { get; set; }
        public int HomeRegularScore => HomeScore[0];
        public int HomeFirstHalfScore => HomeScore[1];
        public int HomeOverTimeScore => HomeScore[2];
        public int HomePenaltyScore => HomeScore[3];
        public int[] AwayScore { get; set; }
        public int AwayRegularScore => AwayScore[0];
        public int AwayFirstHalfScore => AwayScore[1];
        public int AwayOverTimeScore => AwayScore[2];
        public int AwayPenaltyScore => AwayScore[3];
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