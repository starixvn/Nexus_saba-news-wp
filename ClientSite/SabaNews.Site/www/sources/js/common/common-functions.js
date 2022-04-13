import { LeagueType } from './constants';

export default class CommonFunctions {
    static getLeagueName(league) {
        let leagueName = league.leagueFullNameEN;

        if (league.leagueType !== LeagueType.League && league.stage && league.stage.stageNameEN) {
            leagueName += ` :: ${league.stage.stageNameEN}`;
        }

        return leagueName;
    }
}