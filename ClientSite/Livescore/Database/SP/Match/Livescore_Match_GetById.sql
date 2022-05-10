DROP PROCEDURE IF EXISTS Livescore_Match_GetById;

DELIMITER //
CREATE DEFINER=`sabasports`@`%` PROCEDURE `Livescore_Match_GetById`(
	matchId BIGINT, 
    IN sportId SMALLINT UNSIGNED
    /*
		Creator: Hank@20220419
        Revisions: - 20220419@Hank: init
    */
)
BEGIN
     SELECT m.MatchId, m.StartTime, m.EndTime, m.Status
		,m.HomeTeamHalfScore, m.AwayTeamHalfScore, m.HomeTeamTotalScore, m.AwayTeamTotalScore, m.HomeTeamAggregateScore, m.AwayTeamAggregateScore
		,sh.ExtraScore AS HomeTeamExtraScore, sa.ExtraScore AS AwayTeamExtraScore
        ,sh.PenaltyScore AS HomeTeamPenaltyScore, sa.PenaltyScore AS AwayTeamPenaltyScore
        ,m.LeagueId,  l.LeagueNameVN,  l.LeagueNameEN, l.LeagueFullNameEN, l.CurrentRound, l.CurrentSeasonId, l.Icon, s.SeasonId, s.StartTime, s.EndTime, s.Year, ss.StageId, ss.StageNameEN
		,m.HomeTeamId, h.TeamNameEN, h.TeamFullNameEN, h.Icon
        ,m.AwayTeamId, a.TeamNameEN, a.TeamFullNameEN, a.Icon
    FROM livescore_match m
    INNER JOIN livescore_league l ON m.LeagueId = l.LeagueId
	INNER JOIN livescore_team h ON m.HomeTeamId = h.TeamId
	INNER JOIN livescore_team a ON m.AwayTeamId = a.TeamId
    LEFT JOIN livescore_match_statistic sh ON m.MatchId = sh.MatchId AND m.HomeTeamId = sh.TeamId
	LEFT JOIN livescore_match_statistic sa ON m.MatchId = sa.MatchId AND m.AwayTeamId = sa.TeamId
	LEFT JOIN livescore_season s ON m.SeasonId = s.SeasonId
	LEFT JOIN livescore_season_stage ss ON m.StageId = ss.StageId
    WHERE m.MatchId = matchId AND m.SportId = sportId AND m.Status IS NOT NULL AND m.CombinedId IS NULL
    AND NOT EXISTS (SELECT md.MatchId FROM livescore_match_deleted md WHERE m.MatchId = md.MatchId);

END
//
DELIMITER ;	
