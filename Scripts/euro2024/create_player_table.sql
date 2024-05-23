/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isNested="0"></info>*/

CREATE TABLE `livescore_team_player_custom` (
   `PlayerId` int NOT NULL AUTO_INCREMENT,
   `TeamId` bigint NOT NULL,
   `PlayerName` varchar(255) DEFAULT NULL,
   `PlayerFullName` varchar(255) DEFAULT NULL,
   `Birthday` date DEFAULT NULL,
   `ShirtNumber` varchar(10) DEFAULT NULL,
   `ClubName` varchar(255) DEFAULT NULL,
   `Icon` varchar(255) DEFAULT NULL,
   `Position` smallint DEFAULT NULL,
   `UpdatedAt` datetime DEFAULT NULL,
   PRIMARY KEY (`PlayerId`)
 )  ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;