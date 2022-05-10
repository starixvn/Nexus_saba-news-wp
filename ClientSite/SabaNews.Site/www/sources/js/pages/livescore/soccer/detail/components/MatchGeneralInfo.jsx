import React, { Component, Fragment } from 'react';
import { MatchStatus, IncidentStatus, IncidentPosition } from '@jsroot/common/constants';
import IncidentIcon from '@jsroot/components/Icon/IncidentIcon';
import CommonFunctions from '@jsroot/common/common-functions';
import DateTimeService from '@jsroot/common/dateTimeService';
import MatchStage from '@jsroot/components/MatchStage';
import FunctionBar from './FunctionBar';
import TeamIcon from '@jsroot/components/Icon/TeamIcon';
import FavoriteMatches from '@jsroot/components/FavoriteMatches';
import FavouriteIcon from '@jsroot/components/Icon/FavouriteIcon';
import Site from '@jsroot/common/site';

export default class MatchGeneralInfo extends Component {
    getMatchScores = () => {
        const match = this.props.match.generalInfo;
        const startTime = DateTimeService.convertFromUTC(match.startTime);
        switch (match.status) {
            case MatchStatus.NotStarted:
            case MatchStatus.Delay:
            case MatchStatus.Postpone:
            case MatchStatus.Cancel:
            case MatchStatus.TBD:
                return <div className="detail-score-wrapper live before-score">
                    <span>{DateTimeService.toShortTimeFormat(startTime)}</span>
                </div>;
            default:
                return <div className="detail-score-wrapper live">
                    <span>{match.homeTeamExtraScore ? match.homeTeamExtraScore : match.homeTeamTotalScore}</span>
                    <span className="detail-score-divider"> - </span>
                    <span>{match.awayTeamExtraScore ? match.awayTeamExtraScore : match.awayTeamTotalScore}</span>
                </div>;
        }
    };

    getSubScore = () => {
        const match = this.props.match.generalInfo;
        const homePenaltyShootOut = match.homeTeamPenaltyScore;
        const awayPenaltyShootOut = match.awayTeamPenaltyScore;
        const homeAGG = match.homeTeamAggregateScore;
        const awayAGG = match.awayTeamAggregateScore;
        const isPreMatch = CommonFunctions.isPreMatch(match.status);
        return (
            <>
                {
                    (!isPreMatch) &&
                    <li>H1: {match.homeTeamHalfScore} - {match.awayTeamHalfScore}</li>
                }
                {
                    (match.status === MatchStatus.Finished && (homeAGG > 0 || awayAGG > 0)) &&
                    <li>AGG: {homeAGG} - {awayAGG}</li>
                }
                {
                    (match.status === MatchStatus.Finished && (homePenaltyShootOut > 0 || awayPenaltyShootOut > 0)) &&
                    <li>PEN: {homePenaltyShootOut} - {awayPenaltyShootOut}</li>
                }
            </>
        );
    };

    getBallAndCardNumber = position => {
        const { statistic } = this.props.match;
        const incidents = CommonFunctions.produceIncidents(this.props.match.incidents);
        const goalsStatusSelected = [IncidentStatus.Goal, IncidentStatus.Penalty, IncidentStatus.OwnGoal].join(',');
        const goals = incidents.filter(item => goalsStatusSelected.includes(item.type.toString()) && item.position === position && (item.homeScore !== 0 || item.awayScore !== 0));
        let numberRedCard = 0;
        let numberYellowCard = 0;
        if (position === IncidentPosition.Home && statistic.homeStatistic) {
            numberRedCard = statistic.homeStatistic.redCard;
            numberYellowCard = statistic.homeStatistic.yellowCard;
        }
        if (position === IncidentPosition.Away && statistic.awayStatistic) {
            numberRedCard = statistic.awayStatistic.redCard;
            numberYellowCard = statistic.awayStatistic.yellowCard;
        }
        return (
            <div className="participant-detail">
                {
                    goals.map((goal, gkey) => {
                        return (
                            <ul key={gkey} className="participant-detail-group name-player-goal">
                                <li><IncidentIcon type={goal.type} />
                                    <span className="name-player">({goal.timeShow}') - {goal.playerName}</span>
                                </li>
                            </ul>
                        );
                    })
                }
                {
                    numberRedCard > 0 &&
                    <span className="card-league red-card">{numberRedCard}</span>
                }
                {
                    numberYellowCard > 0 &&
                    <span className="card-league yellow-card">{numberYellowCard}</span>
                }
            </div>
        );
    };

    getTeamStatus = isHomeTeam => {
        const { generalInfo } = this.props.match;
        const homeFieldName = isHomeTeam ? 'homeTeam' : 'awayTeam';
        const awayFieldName = isHomeTeam ? 'awayTeam' : 'homeTeam';

        if (generalInfo.status === MatchStatus.Finished) {
            if (generalInfo[`${homeFieldName}PenaltyScore`]
            && generalInfo[`${homeFieldName}PenaltyScore`] > generalInfo[`${awayFieldName}PenaltyScore`]) {
                return <li>PEN</li>;
            } else if (generalInfo[`${homeFieldName}AggregateScore`]
                && generalInfo[`${homeFieldName}AggregateScore`] > generalInfo[`${awayFieldName}AggregateScore`]) {
                return <li>AGG</li>;
            } else {
                return <Fragment />;
            }
        } else {
            return <Fragment />;
        }
    };

    handleClick = event => {
        const url = Site.resolveClientUrl(`lich-thi-dau/bong-da`);
        if (event.ctrlKey) {
            window.open(url, '_blank');
        } else {
            window.location = url;
        }
    };

    handleClickLeague = (event, league) => {
        const today = DateTimeService.formatDateTime(DateTimeService.getCurrentClientDateTime(), 'YYYY-MM-DD');
        const seasonId = league.currentSeasonId ? league.currentSeasonId : 0;
        const url = Site.resolveClientUrl(`lich-thi-dau/bong-da/chi-tiet-giai-dau/${today}/${league.leagueId}/${seasonId}`);
        if (event.ctrlKey) {
            window.open(url, '_blank');
        } else {
            window.location = url;
        }
    };

    render() {
        const { generalInfo } = this.props.match;
        const leagueName = CommonFunctions.getLeagueNameWithStage(generalInfo.league);
        const startTime = DateTimeService.convertFromUTC(generalInfo.startTime);
        const isLiveMatch = CommonFunctions.isLiveMatch(generalInfo.status);
        return (
            <>
                <div className="sticky-header">
                    <ul className="breadcrumb">
                        <li onClick={this.handleClick}><span>LTĐ</span> </li>
                        <li onClick={event => this.handleClickLeague(event, generalInfo.league)}> <span>{leagueName}</span>  </li>
                    </ul>
                    <div className="livescore-league-header-detail">
                        <div className="livescore-league-header-detail-group">
                            <div className="livescore-league-header-detail-logo"><TeamIcon src={generalInfo.league.icon} /></div>
                            <div className="livescore-league-header-detail-name">
                                {leagueName} - <span>{startTime.format('DD/MM/YYYY')}</span>
                            </div>
                            <i className="icon-livescore icon-live-start start-active" />
                        </div>
                        <div className="group-searchbar">
                            <FavoriteMatches isMobile />
                        </div>
                    </div>
                    <div className="duel-participant">
                        <FavouriteIcon matchId={generalInfo.matchId} />
                        <div className="flex-league first-league">
                            <div className="column column-left home-team">
                                <ul className="soccer-team-status">
                                    {this.getTeamStatus(true)}
                                </ul>
                            </div>
                            <div className={`column column-center ${isLiveMatch ? 'live' : ''}`}>
                                {
                                    generalInfo.status !== MatchStatus.NotStarted && <MatchStage match={generalInfo} />
                                }
                            </div>
                            <div className="column column-right away-team">
                                <ul className="soccer-team-status">
                                    {this.getTeamStatus(false)}
                                </ul>
                            </div>
                        </div>
                        <div className="flex-league">
                            <div className="column column-left home-team">
                                <ul className="team">
                                    <li className="team-name">
                                        {CommonFunctions.getTeamName(generalInfo.homeTeam)}
                                    </li>
                                    <li className="team-logo">
                                        <TeamIcon src={generalInfo.homeTeam.icon} className="duel-participant-home-wrapper-logo" />
                                    </li>
                                </ul>
                            </div>
                            <div className="column column-center result-league"> {this.getMatchScores()}</div>
                            <div className="column column-right away-team">
                                <ul className="team">
                                    <li className="team-logo">
                                        <TeamIcon src={generalInfo.awayTeam.icon} />
                                    </li>
                                    <li className="team-name">{CommonFunctions.getTeamName(generalInfo.awayTeam)}</li>
                                </ul>
                            </div>
                        </div>
                        <div className="flex-league">
                            <div className="column column-left home-team sticky-position">
                                <div className="duel-participant-number">
                                    {this.getBallAndCardNumber(IncidentPosition.Home)}
                                </div>
                            </div>
                            <ul className="column column-center detail-score-status">
                                {this.getSubScore()}
                            </ul>
                            <div className="column column-right away-team sticky-position">
                                <div className="duel-participant-number">
                                    {this.getBallAndCardNumber(IncidentPosition.Away)}
                                </div>
                            </div>
                        </div>
                    </div>
                    <FunctionBar />
                </div>
                <div className="detail-fixed">
                    <div className="sticky-header-scroll">
                        <ul className="breadcrumb">
                            <li onClick={this.handleClick}><span>LTĐ</span> </li>
                            <li onClick={event => this.handleClickLeague(event, generalInfo.league)}> <span>{leagueName}</span>  </li>
                        </ul>
                        <div className="livescore-league-header-detail">
                            <div className="livescore-league-header-detail-group">
                                <div className="livescore-league-header-detail-logo"><TeamIcon src={generalInfo.league.icon} /></div>
                                <div className="livescore-league-header-detail-name">
                                    {leagueName} - <span>{startTime.format('DD/MM/YYYY')}</span>
                                </div>
                                <i className="icon-livescore icon-live-start start-active" />
                            </div>
                            <div className="group-searchbar">
                                <FavoriteMatches isMobile />
                            </div>
                        </div>
                        <div className="duel-participant">
                            <FavouriteIcon matchId={generalInfo.matchId} />
                            <div className="flex-league first-league">
                                <div className="column column-left home-team">
                                    <ul className="soccer-team-status">
                                        {this.getTeamStatus(true)}
                                    </ul>
                                </div>
                                <div className={`column column-center ${isLiveMatch ? 'live' : ''}`}>
                                    {
                                        generalInfo.status !== MatchStatus.NotStarted && <MatchStage match={generalInfo} />
                                    }
                                </div>
                                <div className="column column-right away-team">
                                    <ul className="soccer-team-status">
                                        {this.getTeamStatus(false)}
                                    </ul>
                                </div>
                            </div>
                            <div className="flex-league">
                                <div className="column column-left home-team">
                                    <ul className="team">
                                        <li className="team-name">
                                            {CommonFunctions.getTeamName(generalInfo.homeTeam)}
                                        </li>
                                        <li className="team-logo">
                                            <TeamIcon src={generalInfo.homeTeam.icon} className="duel-participant-home-wrapper-logo" />
                                        </li>
                                    </ul>
                                </div>
                                <div className="column column-center result-league"> {this.getMatchScores()}</div>
                                <div className="column column-right away-team">
                                    <ul className="team">
                                        <li className="team-logo">
                                            <TeamIcon src={generalInfo.awayTeam.icon} />
                                        </li>
                                        <li className="team-name">{CommonFunctions.getTeamName(generalInfo.awayTeam)}</li>
                                    </ul>
                                </div>
                            </div>
                            <div className="flex-league">
                                <div className="column column-left home-team sticky-position">
                                    <div className="duel-participant-number">
                                        {this.getBallAndCardNumber(IncidentPosition.Home)}
                                    </div>
                                </div>
                                <ul className="column column-center detail-score-status">
                                    {this.getSubScore()}
                                </ul>
                                <div className="column column-right away-team sticky-position">
                                    <div className="duel-participant-number">
                                        {this.getBallAndCardNumber(IncidentPosition.Away)}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <FunctionBar />
                    </div>
                </div>
            </>
        );
    }
}