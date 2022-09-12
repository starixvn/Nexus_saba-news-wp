import { LeagueType, IncidentStatus, MatchStatus, VARResultCode } from './constants';
import LangHelper from '@jsroot/common/langHelper';
import Site from '@jsroot/common/site';
import DateTimeService from '@jsroot/common/dateTimeService';

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

        if (league.leagueType !== LeagueType.League && league.leagueType !== LeagueType.Friendly && stage) {
            leagueName += ` - ${this.getStageName(stage)}`;
        }

        return leagueName;
    }

    static getStageName(stage) {
        return stage.stageNameVN ? stage.stageNameVN : stage.stageNameEN;
    }

    static getCountryIcon(country) {
        if(!country) return null;
        return country.countryLogoCustom ? country.countryLogoCustom : country.countryLogo;
    }

    static produceIncidents(incidents) {
        let summary =  [];
        let isInjurytime = false;
        let homeScore = 0;
        let awayScore = 0;
        incidents.map((item, index) => {
            let incident = {...item};
            incident.isPenaltyShootout = false;
            if(index > 0 ) {
                const lastIncident = incidents[index - 1];
                if(lastIncident.time === incident.time && lastIncident.second === incident.second 
                    && incident.playerId > 0
                    && lastIncident.playerId === incident.playerId) {
                    return false;
                }
            }
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
            if ((incident.type === IncidentStatus.Penalty || incident.type === IncidentStatus.PenaltyMissed)
                && incident.homeScore === 0 && incident.awayScore === 0) {
                incident.isPenaltyShootout = true;
            }
            if(this.checkIncidentTypeToUpdateScore(item.type)) {
                homeScore = item.homeScore;
                awayScore = item.awayScore;
            } else {
                incident.homeScore = homeScore;
                incident.awayScore = awayScore;
            }
            incident.timeShow = (item.time < 0 || item.second < 0 ) ? '' : time;
            summary.push(incident);
        });
        return summary;
    }

    static getTeamName(team) {
        return team.teamNameEN ? team.teamNameEN : team.teamFullNameEN;
    }

    static isLiveMatch(statusId) {
        return statusId === MatchStatus.FirstHalf
            || statusId === MatchStatus.SecondHalf
            || statusId === MatchStatus.Intermission
            || statusId === MatchStatus.ExtraTime
            || statusId === MatchStatus.PenaltyShootout;
    }

    static isPreMatch(statusId) {
        return statusId === MatchStatus.NotStarted
            || statusId === MatchStatus.Postpone
            || statusId === MatchStatus.Cancel
            || statusId === MatchStatus.TBD;
    }

    static getFavouriteMatches() {
        let matchIds = localStorage.getItem('favourite-matches');
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
        let favCountMobile = document.querySelector('.livescore-like .count-start .mobile-count');
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
                favCountMobile.classList.add('count-number');
                favCountMobile.innerHTML = count;
            } else {
                favCountMobile.classList.remove('count-number');
                favCountMobile.innerHTML = "";
            }
        }
    };

    static updateMobileMenuMatchCount(matches) {
        const count = matches.length;
        const favMatch = document.querySelector('.mobile-fav');
        if (favMatch) {
            if (count > 0) {
                favMatch.classList.add('number-count-fav');
                favMatch.innerHTML = count;
            } else {
                favMatch.classList.remove('number-count-fav');
                favMatch.innerHTML = '';
            }
        }
    }

    static updateFavouriteMatch(matchId) {
        if (!matchId) {
            return null;
        }

        let favouriteMatches =  this.getFavouriteMatches();
        const favourite = favouriteMatches.find(item => item === matchId);
        let isFavourite = false;
        if(favourite) {
            favouriteMatches = favouriteMatches.filter(item =>  item !== matchId);
        } else {
            favouriteMatches.push(matchId);
            isFavourite = true;
        }

        localStorage.setItem('favourite-matches', JSON.stringify(favouriteMatches));
        this.updateFavouriteMatchesCount(favouriteMatches);
        this.updateMobileMenuMatchCount(favouriteMatches);
        return isFavourite;
    }


    static getFavLeagues() {
        let favLeagues = localStorage.getItem('favourite-leagues');
        if(!favLeagues) {
            favLeagues = [];
        } else {
            favLeagues = JSON.parse(favLeagues.toString());
            favLeagues = favLeagues.filter(item => item !== null);
        }
        return favLeagues;
    }

    static updateFavLeague(league) {
        let favLeague = this.getFavLeagues();
        const favourite = favLeague.find(item => item.leagueId === league.leagueId);
        let isFavourite = false;
        if (favourite) {
            favLeague = favLeague.filter(item => item.leagueId !== league.leagueId);
        } else {
            const leagueTmp = {
                leagueId: league.leagueId,
                leagueNameEN: league.leagueNameEN,
                leagueFullNameEN: league.leagueFullNameEN,
                leagueNameVN: league.leagueNameVN,
                icon: league.icon,
                currentSeasonId: league.season ? league.season.seasonId : league.currentSeasonId
            }
            favLeague.push(leagueTmp);
            isFavourite = true;
        }
    }

    static refreshDefaultLeague(leagues) {
        let isDefault = false;
        const currentFavLeague = this.getFavLeagues();
        leagues = leagues.reverse();
        for (let i = 0; i < leagues.length; i++) {
            if (currentFavLeague[i] && currentFavLeague[i].leagueId === leagues[i].leagueId) {
                isDefault = true;
            } else {
                isDefault = false;
                break;
            }
        }
        if (isDefault) {
            localStorage.setItem('isDefaultLeague', 1);
        } else {
            localStorage.setItem('isDefaultLeague', 0);
        }
        return currentFavLeague;
    }

    static refreshFavLeagues(leagues) {
        let isDefaultLeague = localStorage.getItem('isDefaultLeague');
        if(localStorage.getItem('favourite-leagues') !== null && isDefaultLeague === null) {
            return this.refreshDefaultLeague(leagues);
        }
        const currentLeague = this.getFavLeagues();
        const orders = currentLeague.map(item => item.order);
        if(orders.length === 0) {
            localStorage.setItem('isDefaultLeague', 1);
            isDefaultLeague = 1;
        }
        localStorage.removeItem('favourite-leagues');
        const favLeagues = [];
        leagues = orders.length > 0 && parseInt(isDefaultLeague) === 0  ? leagues : leagues.reverse();
        leagues.map((league, index) => {
            const leagueTmp = {
                leagueId: league.leagueId,
                leagueNameEN: league.leagueNameEN,
                leagueFullNameEN: league.leagueFullNameEN,
                leagueNameVN: league.leagueNameVN,
                icon: league.icon,
                currentSeasonId: league.season ? league.season.seasonId : league.currentSeasonId,
                order: index + 1
            };
            favLeagues.push(leagueTmp);
        });
        localStorage.setItem('favourite-leagues', JSON.stringify(favLeagues));
        return favLeagues;
    }

    static getFavLeagueParam() {
        const favLeague = this.getFavLeagues();
        const isDefaultLeague = localStorage.getItem('isDefaultLeague');
        const currentFavLeague = localStorage.getItem('favourite-leagues');
        let leagueIds = '';
        if (currentFavLeague === null
        || parseInt(isDefaultLeague) == 1
        || (currentFavLeague !== null && isDefaultLeague === null) ) {
            leagueIds  = null;
        } else if (favLeague.length > 0) {
            const orders = favLeague.sort((item1, item2) => (item1.order - item2.order));
            leagueIds =  JSON.stringify(orders.map(a => a.leagueId));
        }
        return leagueIds;
    }

    static checkIncidentTypeToUpdateScore(type) {
        return type === IncidentStatus.Penalty || type === IncidentStatus.Goal || type === IncidentStatus.OwnGoal;
    }

    static checkIncidentGeneralType(type) {
        return type === IncidentStatus.Goal || type === IncidentStatus.YellowCard || type === IncidentStatus.RedCard || type === IncidentStatus.YellowTurnRedCard
            || type === IncidentStatus.OvertimeEnds || type === IncidentStatus.PenaltyMissed || type === IncidentStatus.OwnGoal || type === IncidentStatus.VAR
            || type === IncidentStatus.Penalty || type === IncidentStatus.Substitution;
    }

    static mappingVarResultText(status) {
        switch (status) {
            case VARResultCode.Unknown:
                return LangHelper.getResource('Unknown');
            case VARResultCode.GoalValid:
                return LangHelper.getResource('GoalValid');
            case VARResultCode.GoalInValid:
                return LangHelper.getResource('GoalInValid');
            case VARResultCode.PenaltyValid:
                return LangHelper.getResource('PenaltyValid');
            case VARResultCode.PenaltyCancelled:
                return LangHelper.getResource('PenaltyCancelled');
            case VARResultCode.RedCardValid:
                return LangHelper.getResource('RedCardValid');
            case VARResultCode.RedCardCancelled:
                return LangHelper.getResource('RedCardCancelled');
            case VARResultCode.PlayPenaltyVerification:
                return LangHelper.getResource('PlayPenaltyVerification');
            case VARResultCode.PlayPenaltyChange:
                return LangHelper.getResource('PlayPenaltyChange');
            case VARResultCode.MaintainOriginalJudgment:
                return LangHelper.getResource('MaintainOriginalJudgment');
            case VARResultCode.SendPenaltyChange:
                return LangHelper.getResource('SendPenaltyChange');
            default:
                return '';
        }
    }

    static getLeaguePageUrl(league) {
        const leagueName = Site.toSlug(this.getLeagueName(league));
        const today = DateTimeService.formatDateTime(DateTimeService.getCurrentClientDateTime(), 'YYYY-MM-DD');
        const seasonId = league.currentSeasonId ? league.currentSeasonId : 0;
        const url = Site.resolveClientUrl(`lich-thi-dau/bong-da/${leagueName}/${seasonId}/${today}/${league.leagueId}`);
        return url;
    }
}