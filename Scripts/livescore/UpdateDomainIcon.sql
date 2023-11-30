/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isNested="0"></info>*/

update sabasports.livescore_league
set Icon = replace(Icon, 'huainan710.com', 'abjing.com')
where Icon like '%huainan710.com%';

update sabasports.livescore_team
set Icon = replace(Icon, 'huainan710.com', 'abjing.com')
where Icon like '%huainan710.com%';

update sabasports.livescore_team
set CoachIcon = replace(CoachIcon, 'huainan710.com', 'abjing.com')
where CoachIcon like '%huainan710.com%';

update sabasports.livescore_player
set Icon = replace(Icon, 'huainan710.com', 'abjing.com')
where Icon like '%huainan710.com%';