import React, { Component, Fragment } from 'react';
import Site from '@jsroot/common/site';
import { MatchStatus, IncidentStatus, IncidentPosition } from '@jsroot/common/constants';
import IncidentIcon from '@jsroot/components/Icon/IncidentIcon'
import CommonFunctions from '@jsroot/common/common-functions';
import DateTimeService from '@jsroot/common/dateTimeService';
import MatchStage from '@jsroot/components/MatchStage'

export default class MatchGenetalInfo extends Component {
    constructor(props) {
        super(props);
    }
    getMatchScores = () => {
        const match = this.props.match.generalInfo;
        const homePenaltyShootOut = match.homeTeamPenaltyScore
        const awayPenaltyShootOut = match.awayTeamPenaltyScore;
        const homeAGG = match.homeTeamAggregateScore;
        const awayAGG = match.awayTeamAggregateScore;
        switch (match.status) {
            case MatchStatus.NotStarted:
            case MatchStatus.Delay:
            case MatchStatus.Postponed:
            case MatchStatus.Cancel:
            case MatchStatus.TBD:
                return (<div className="detail-score-wrapper live before-score">
                    <span>VS</span>
                </div>)
            default:
                return <>
                    <div className="detail-score-wrapper live">
                        <span>{match.homeTeamExtraScore ? match.homeTeamExtraScore : match.homeTeamTotalScore}</span>
                        <span className="detail-score-divider"> - </span>
                        <span>{match.awayTeamExtraScore ? match.awayTeamExtraScore : match.awayTeamTotalScore}</span>
                    </div>
                    <div className="detail-score-status">
                        <p>H1: {match.homeTeamHalfScore} - {match.awayTeamHalfScore}</p>
                        {
                            (homeAGG !== 0 || awayAGG !== 0) &&
                            <p>AGG: {homeAGG} - {awayAGG}</p>
                        }
                        {
                            (homePenaltyShootOut !== 0 || awayPenaltyShootOut !== 0) &&
                            <p>Pen: {homePenaltyShootOut} - {awayPenaltyShootOut}</p>
                        }
                    </div>
                </>;
        }
    };

    getBallAndCardNumber = (position) => {
        const { statistic } = this.props.match;
        const incidents = CommonFunctions.produceIncidents(this.props.match.incidents);
        let goalsStatusSelected = [IncidentStatus.Goal, IncidentStatus.Penalty, IncidentStatus.OwnGoal].join(',');
        const goals = incidents.filter(item => goalsStatusSelected.includes(item.type.toString()) && item.position === position && (item.homeScore !== 0 || item.awayScore !== 0));
        let numberRedCard = 0;
        let numberYellowCard = 0;
        if (position === IncidentPosition.Home && statistic.homeStatistic) {
            numberRedCard = statistic.homeStatistic.redCard;
            numberYellowCard = statistic.homeStatistic.yellowCard
        }
        if (position === IncidentPosition.Away && statistic.awayStatistic) {
            numberRedCard = statistic.awayStatistic.redCard;
            numberYellowCard = statistic.awayStatistic.yellowCard
        }
        return (
            <div className="participant-detail">
                {
                    goals.map((goal, gkey) => {
                        return (
                            <div key={gkey} className="participant-detail-group">
                                <IncidentIcon type={goal.type} />
                                <span className="name-player">({goal.timeShow}') - {goal.playerName}</span>
                            </div>
                        )
                    })
                }
                {
                    numberRedCard !== 0 &&
                    <span className="card-league red-card">{numberRedCard}</span>
                }
                {
                    numberYellowCard !== 0 &&
                    <span className="card-league yellow-card">{numberYellowCard}</span>
                }
            </div>
        )
    }

    getTeamStatus = (isHomeTeam) => {
        const { generalInfo } = this.props.match;
        const homeFieldName = isHomeTeam ? 'homeTeam' : 'awayTeam';
        const awayFieldName = isHomeTeam ? 'awayTeam' : 'homeTeam';

        switch (generalInfo.status) {
            case MatchStatus.Finished:
                if (generalInfo[`${homeFieldName}PenaltyScore`]
                    && generalInfo[`${homeFieldName}PenaltyScore`] > generalInfo[`${awayFieldName}PenaltyScore`]) {
                    return <li>PEN</li>;
                } else if (generalInfo[`${homeFieldName}AggregateScore`]
                    && generalInfo[`${homeFieldName}AggregateScore`] > generalInfo[`${awayFieldName}AggregateScore`]) {
                    return <li>AGG</li>;
                } else {
                    return <Fragment />;
                }
            default:
                return <Fragment />;
        }
    }

    render() {
        const { generalInfo } = this.props.match;
        const leagueName = CommonFunctions.getLeagueNameWithStage(generalInfo.league);
        const startTime = DateTimeService.convertFromUTC(generalInfo.startTime);
        return (
            <>
                <div className="livescore-league-header-detail">
                    <div className="livescore-league-header-detail-logo"><img loading="lazy" src={generalInfo.league.icon} /></div>
                    <div className="livescore-league-header-detail-name">
                        {leagueName} - <span>{startTime.format("DD/MM/YYYY")}</span> <i className="icon-livescore icon-live-start start-active" />
                    </div>
                </div>
                <div className="duel-participant">
                    <div className="duel-participant-home-wrapper">
                        <ul className="soccer-team-status">
                            {this.getTeamStatus(true)}
                        </ul>
                        <div className="duel-participant-home-wrapper-image">
                            <div className="duel-participant-home-wrapper-name">{generalInfo.homeTeam.teamNameEN}</div>
                            <a className="duel-participant-home-wrapper-logo">
                                <img alt={generalInfo.homeTeam.teamNameEN} loading="lazy"
                                    src={generalInfo.homeTeam.icon} />
                            </a>
                        </div>
                        <div className="duel-participant-number">
                            {this.getBallAndCardNumber(IncidentPosition.Home)}
                        </div>
                    </div>

                    <div className="duel-participant-result">
                        <div className="duel-participant-result-time">
                            <MatchStage match={generalInfo} />
                        </div>
                        <div className="duel-participant-result-score">
                            {this.getMatchScores()}
                        </div>
                    </div>

                    <div className="duel-participant-away-wrapper">
                        <ul className="soccer-team-status">
                            {this.getTeamStatus(false)}
                        </ul>
                        <div className="duel-participant-away-wrapper-image">
                            <a>
                                <img width="58" height="58" alt={generalInfo.awayTeam.teamNameEN} loading="lazy"
                                    src={generalInfo.awayTeam.icon} />
                            </a>
                            <div className="duel-participant-away-wrapper-name">{generalInfo.awayTeam.teamNameEN}</div>
                        </div>
                        <div className="duel-participant-number">
                            {this.getBallAndCardNumber(IncidentPosition.Away)}
                        </div>
                    </div>
                </div>
            </>
        );
    }
}
