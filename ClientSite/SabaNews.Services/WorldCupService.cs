namespace SabaNews.Services
{
    using SabaNews.Core.Models;
    using SabaNews.Core.Services;
    using SabaNews.Criteria.Event.WorldCup;
    using SabaNews.Livescore.Models.League;
    using SabaNews.Livescore.Models.Team;
    using SabaNews.Models.Event.WorldCup;
    using SabaNews.Services.Interfaces;
    using System.Collections.Generic;
    using System.Linq;
    using System.Threading.Tasks;

    public class WorldCupService : IWorldCupService
    {
        private readonly IBaseService baseService;

        public WorldCupService(IBaseService baseService)
        {
            this.baseService = baseService;
        }

        public async Task<IList<UserMinigameModel>> GetMinigameByUserStageAsync(GetMinigameByUserStageCriteria criteria)
        {
            var result = await baseService.GetDataAsync<UserMinigameModel>(criteria);
            return result.ToList();
        }

        public async Task<CommandResult> UpdateMinigameVoteAsync(UpdateMinigameVoteCommand cmd)
        {
            return await baseService.ExecuteNonQueryAsync(cmd);
        }

        public async Task<MinigameMatchVote> GetMinigameVoteMatchAsync(GetMinigameVoteMatchCriteria criteria)
        {
            var result = await baseService.GetDataEntityAsync<MinigameMatchVote>(criteria);
            return result;
        }

        public async Task<(League, IList<VoteMatch>)> GetMinigameVotedMatchByUserStageAsync(GetMinigameVotedMatchByUserStageCriteria criteria)
        {
            (var leagues, var floatMatches) = await baseService.FetchMultipleAsync<League, MinigameFloatVotedMatch>(criteria);
            var league = leagues.FirstOrDefault();

            var matches = new List<VoteMatch>();
            foreach (MinigameFloatVotedMatch match in floatMatches)
            {
                var stage = match.StageId.HasValue
                    ? new SeasonStage
                    {
                        StageId = match.StageId.Value,
                        StageNameEN = match.StageNameEN,
                        StageNameVN = match.StageNameVN,
                        Mode = match.StageMode,
                        GroupCount = match.GroupCount,
                        RoundCount = match.RoundCount,
                        OrderIndex = match.OrderIndex
                    }
                    : null;
                matches.Add(new VoteMatch
                {
                    MatchId = match.MatchId,
                    StartTime = match.StartTime,
                    EndTime = match.EndTime,
                    Status = match.Status,
                    GroupNum = match.GroupNum,
                    Round = match.Round,
                    Vote = match.Vote,
                    UpdatedTime = match.UpdatedDate,
                    HomeTeamHalfScore = match.HomeTeamHalfScore,
                    AwayTeamHalfScore = match.AwayTeamHalfScore,
                    HomeTeamTotalScore = match.HomeTeamTotalScore,
                    AwayTeamTotalScore = match.AwayTeamTotalScore,
                    HomeTeamAggregateScore = match.HomeTeamAggregateScore,
                    AwayTeamAggregateScore = match.AwayTeamAggregateScore,
                    HomeTeamExtraScore = match.HomeTeamExtraScore,
                    AwayTeamExtraScore = match.AwayTeamExtraScore,
                    HomeTeamPenaltyScore = match.HomeTeamPenaltyScore,
                    AwayTeamPenaltyScore = match.AwayTeamPenaltyScore,
                    HomeTeam = new Team
                    {
                        TeamId = match.HomeTeamId,
                        TeamNameEN = match.HomeTeamNameEN,
                        TeamFullNameEN = match.HomeTeamFullNameEN,
                        TeamNameVN = match.HomeTeamNameVN,
                        Icon = match.HomeTeamIcon,
                        IconVN = match.HomeTeamIconVN
                    },
                    AwayTeam = new Team
                    {
                        TeamId = match.AwayTeamId,
                        TeamNameEN = match.AwayTeamNameEN,
                        TeamFullNameEN = match.AwayTeamFullNameEN,
                        TeamNameVN = match.AwayTeamNameVN,
                        Icon = match.AwayTeamIcon,
                        IconVN = match.AwayTeamIconVN
                    },
                    Stage = stage
                });
            }

            return (league, matches);
        }
    }
}
