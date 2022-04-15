import { LeagueType, IncidentStatus } from './constants';
import Site from '@jsroot/common/site';

export default class CommonFunctions {
    static getLeagueName(league) {
        let leagueName = league.leagueFullNameEN;

        if (league.leagueType !== LeagueType.League && league.stage && league.stage.stageNameEN) {
            leagueName += ` :: ${league.stage.stageNameEN}`;
        }

        return leagueName;
    }

    static produceIncidents(incidents) {
        let summary =  [];
        let isInjurytime = false;
        let homeScore = 0;
        let awayScore = 0;
        incidents.map(item => {
            let incident = {...item};
            let time = item.time + (item.second % 60 > 0 ? 1 : 0);
            if(incident.type === IncidentStatus.InjuryTime && (incident.time >= 45 && incident.time < 105)) {
                isInjurytime = true;
            }
            if(incident.type ===  IncidentStatus.Halftime) {
                isInjurytime = false;
            }
            if(incident.type ===  IncidentStatus.End) {
                isInjurytime = false;
            }
            if(isInjurytime) {
                if(time > 45 && time < 90) {
                    time = "45" + "+" + (time - 45);
                } else {
                    time = "90" + "+" + (time - 90);
                }
            }
            if(Site.CheckIncidentTypeToUpdateScore(item.type)) {
                homeScore = item.homeScore;
                awayScore = item.awayScore;
            } else {
                incident.homeScore = homeScore;
                incident.awayScore = awayScore;
            }
            incident.timeShow = time;
            summary.push(incident);
        });
        return summary;
    }

    static getTeamName(team) {
        return team.teamNameEN ? team.teamNameEN : team.teamFullNameEN;
    }
}