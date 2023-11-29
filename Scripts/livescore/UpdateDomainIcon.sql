/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isNested="0"></info>*/

update sabasports.livescore_league
set Icon = replace(Icon, 'huainan710.com', 'abjing.com');

update sabasports.livescore_team
set Icon = replace(Icon, 'huainan710.com', 'abjing.com')
	,CoachIcon = replace(Icon, 'huainan710.com', 'abjing.com');

update sabasports.livescore_player
set Icon = replace(Icon, 'huainan710.com', 'abjing.com');