/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isNested="0"></info>*/


 CREATE TABLE `livescore_league_minigame` (
   `UserId` bigint NOT NULL,
   `MatchId` bigint NOT NULL,
   `Stage` smallint NOT NULL,
   `LeagueId` bigint NOT NULL,
   `SeasonId` bigint NOT NULL,
   `TeamId` bigint NOT NULL,
   `CreatedDate` datetime DEFAULT NULL,
   `UpdatedDate` datetime DEFAULT NULL,
   PRIMARY KEY (`UserId`,`MatchId`,`Stage`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;