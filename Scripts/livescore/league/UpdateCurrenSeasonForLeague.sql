/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isNested="0"></info>*/

UPDATE sabasports.livescore_league l 
SET l.CurrentSeasonId = 11627 /* old  value = 10899*/
WHERE l.LeagueId = 12000;

UPDATE sabasports.livescore_league l 
SET l.CurrentSeasonId = 11666 /* old  value = 10927*/
WHERE l.LeagueId = 12001;

UPDATE sabasports.livescore_league l 
SET l.CurrentSeasonId = 11617 /* old  value = 10900*/
WHERE l.LeagueId = 12002;

UPDATE sabasports.livescore_league l 
SET l.CurrentSeasonId = 11771 /* old  value = 11058*/
WHERE l.LeagueId = 12036;