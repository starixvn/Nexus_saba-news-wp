import { LeagueType, IncidentStatus } from './constants';
import Site from '@jsroot/common/site';

export default class CommonFunctions {
    static getLeagueName(league) {
        let leagueName = '';
        if (league.leagueNameVN) {
            leagueName = league.leagueNameVN;
        } else {
            leagueName = league.leagueNameEN ? league.leagueNameEN : league.leagueFullNameEN;
        }

        return leagueName;
    }

    static getLeagueNameWithStage(league) {
        let leagueName = this.getLeagueName(league);

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
                } else if(time > 90 && time < 105) {
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

    static getFavouriteMatches() {
        let matchIds = localStorage.getItem("favourite-matches");
        if(!matchIds) {
            matchIds = [];
        } else {
            matchIds = JSON.parse(matchIds);
        }
        return matchIds;
    }

    static updateFavouriteMatches(matches) {
        document.querySelector('.number-like').innerHTML = matches.length;
    }
}