ALTER TABLE sabasports.livescore_team
ADD TeamNameVN varchar(255) NULL
AFTER TeamFullNameCN;

ALTER TABLE sabasports.livescore_team
ADD IconVN varchar(255) NULL
AFTER Icon;

ALTER TABLE sabasports.livescore_season_stage
ADD StageNameVN varchar(255) NULL
AFTER StageNameEN;

CREATE TABLE sabasports.`livescore_season_standings_extra` (
  `SeasonId` bigint NOT NULL,
  `StageId` bigint NOT NULL,
  `TeamId` bigint NOT NULL,
  `GroupName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Position` smallint DEFAULT NULL,
  `Total` smallint DEFAULT NULL,
  `Won` smallint DEFAULT NULL,
  `Loss` smallint DEFAULT NULL,
  `Draw` smallint DEFAULT NULL,
  `Goals` smallint DEFAULT NULL,
  `GoalsAgainst` smallint DEFAULT NULL,
  `GoalDiff` smallint DEFAULT NULL,
  `Points` smallint DEFAULT NULL,
  `UpdatedDate` datetime DEFAULT NULL,
  PRIMARY KEY (`SeasonId`,`StageId`,`TeamId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

 CREATE TABLE sabasports.`livescore_match_custom` (
  `MatchId` bigint NOT NULL,
  `Status` smallint DEFAULT NULL,
  `StartTime` datetime DEFAULT NULL,
  `Round` smallint DEFAULT NULL,
  `HomeTeamHalfScore` smallint DEFAULT NULL,
  `AwayTeamHalfScore` smallint DEFAULT NULL,
  `HomeTeamExtraScore` smallint DEFAULT NULL,
  `AwayTeamExtraScore` smallint DEFAULT NULL,
  `HomeTeamAggregateScore` smallint DEFAULT NULL,
  `AwayTeamAggregateScore` smallint DEFAULT NULL,
  `HomeTeamPenaltyScore` smallint DEFAULT NULL,
  `AwayTeamPenaltyScore` smallint DEFAULT NULL,
  `UpdatedDate` datetime DEFAULT NULL,
  PRIMARY KEY (`MatchId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;