/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isNested="0"></info>*/

update sabasports.livescore_league
set Icon = replace(Icon, 'abjing.com', 'world-media.cn')
where Icon like '%abjing.com%';

update sabasports.livescore_team
set Icon = replace(Icon, 'abjing.com', 'world-media.cn')
where Icon like '%abjing.com%';

update sabasports.livescore_team
set CoachIcon = replace(CoachIcon, 'abjing.com', 'world-media.cn')
where CoachIcon like '%abjing.com%';

update sabasports.livescore_player
set Icon = replace(Icon, 'abjing.com', 'world-media.cn')
where Icon like '%abjing.com%';