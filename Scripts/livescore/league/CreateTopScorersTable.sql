/*<info serverAlias="SSMS-sabasports" databaseType="2" executers="sabasports" isNested="0"></info>*/
CREATE TABLE livescore_top_scorers (
    PlayerId BIGINT NOT NULL,
    SeasonId BIGINT NOT NULL,
    LeagueId BIGINT NOT NULL,
    TeamId BIGINT NOT NULL,
    Goals INT NOT NULL,
    Assists INT NOT NULL,
    RowNum INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    Top INT NOT NULL
);
