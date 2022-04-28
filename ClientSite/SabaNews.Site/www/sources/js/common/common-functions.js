import { LeagueType, IncidentStatus } from './constants';
import Site from './site';

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
        const stage = league.stage;

        if (league.leagueType !== LeagueType.League && stage) {
            leagueName += ` :: ${this.getStageName(stage)}`;
        }

        return leagueName;
    }

    static getStageName(stage) {
        return stage.stageNameVN ? stage.stageNameVN : stage.stageNameEN;
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
            matchIds = JSON.parse(matchIds.toString());
            matchIds = matchIds.filter(match => match !== null);
        }
        return matchIds;
    }

    static updateFavouriteMatchesCount(matches) {
        const count = matches.length;
        let favCountDesktop = document.querySelector('.livescore-like .like-count');
        let favCountMobile = document.querySelector('.livescore-like .icon-livescore');
        if(favCountDesktop) {
            if(count > 0) {
                favCountDesktop.classList.add('number-like');
                favCountDesktop.innerHTML = count;
            } else {
                favCountDesktop.classList.remove('number-like');
                favCountDesktop.innerHTML = "";
            }
        }
        if(favCountMobile) {
            if(count > 0) {
                favCountMobile.setAttribute('data-badge', count);
            } else {
                favCountMobile.removeAttribute('data-badge');
            }
        }
    }

    static updateFavouriteMatch(matchId) {
        if(!matchId) return null;
        let favouriteMatches =  this.getFavouriteMatches();
        const favourite = favouriteMatches.find(item => item === matchId);
        let isFavourite = false;
        if(favourite) {
            favouriteMatches = favouriteMatches.filter(item =>  item !== matchId);
            isFavourite = false;
        } else {
            favouriteMatches.push(matchId);
            isFavourite = true;
        }
        localStorage.setItem('favourite-matches', JSON.stringify(favouriteMatches));
        this.updateFavouriteMatchesCount(favouriteMatches);
        return isFavourite;
    }
}