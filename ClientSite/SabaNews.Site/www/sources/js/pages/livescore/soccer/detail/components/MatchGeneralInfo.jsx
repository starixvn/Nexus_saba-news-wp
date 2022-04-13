import React, { Component } from 'react';
import Site from '@jsroot/common/site';
import { MatchStatus, IncidentStatus, IncidentPosition } from '@jsroot/common/constants';
import  IncidentIcon from '@jsroot/components/Icon/IncidentIcon'
import CommonFunctions from '@jsroot/common/common-functions';
import DateTimeService from '@jsroot/common/dateTimeService';

export default class MatchGenetalInfo extends Component {
    constructor(props) {
        super(props);
    }
    getMatchScores = match => {
        const { statistic } = this.props.match;
        if(!statistic) {
            return (<div className="detail-score-wrapper live">
                        <span>VS</span>
                    </div>);
        }
        const numberHomePenaltyShootOut = statistic.homeStatistic.penaltyScore
        const numberAwayPenaltyShootOut = statistic.awayStatistic.penaltyScore;
        switch (match.status) {
            case MatchStatus.NotStarted:
            case MatchStatus.Delay:
            case MatchStatus.Postponed:
            case MatchStatus.Cancel:
            case MatchStatus.TBD:
                return (<div className="detail-score-wrapper live">
                            <span>VS</span>
                        </div>)
            default:
                return <>
                        <div className="detail-score-wrapper live">
                            <span>{match.homeTeamTotalScore}</span>
                            <span className="detail-score-divider">-</span>
                            <span>{match.awayTeamTotalScore}</span>
                        </div>
                        <div className="detail-score-status">
                            <p>H1: {match.homeTeamHalfScore} - {match.awayTeamHalfScore}</p>
                            {
                                (numberHomePenaltyShootOut !== 0 || numberAwayPenaltyShootOut !== 0) &&
                                <p>Pen: {numberHomePenaltyShootOut} - {numberAwayPenaltyShootOut}</p>
                            }
                        </div>
                </>;
        }
    };

    getBallAndCardNumber = (position) => {
        const { incidents, statistic } = this.props.match;
        if(!incidents) {
            return null;
        }
        let goalsStatusSelected = [IncidentStatus.Goal, IncidentStatus.Penalty, IncidentStatus.OwnGoal].join(',');
        const goals = incidents.filter(item => goalsStatusSelected.includes(item.type.toString())  && item.position === position && (item.homeScore !== 0 || item.awayScore !== 0));
        let numberRedCard = 0;
        let numberYellowCard = 0;
        if(position === IncidentPosition.Home) {
            numberRedCard = statistic.homeStatistic.redCard;
            numberYellowCard = statistic.homeStatistic.yellowCard
        } else {
            numberRedCard = statistic.awayStatistic.redCard;
            numberYellowCard = statistic.awayStatistic.yellowCard
        }
        return (
            <div className="participant-detail">
                {
                    goals.map( (goal, gkey) => {
                        return (
                            <div key={gkey}>
                                <span className="icon-th">
                                    <IncidentIcon type = {goal.type}/>
                                </span>
                                <span>({goal.time}') - {goal.playerName}</span>
                            </div>
                        )
                    })
                }
                {
                    (numberRedCard !== 0 || numberYellowCard !== 0) &&
                    <>
                        <span className="red-card">{numberRedCard}</span>
                        <span className="yellow-card">{numberYellowCard}</span>
                    </>
                }
            </div>
        )
    }

    render() {
        const { generalInfo, incidents, statistic } = this.props.match;
        const leagueName = CommonFunctions.getLeagueName(generalInfo.league);
        const startTime = DateTimeService.convertFromUTC(generalInfo.startTime);
        return (
            <>
                <div className="tournament-header">{leagueName} - {startTime.format("DD/MM/YYYY")}</div>
                <div className="duel-participant">
                    <div className="duel-participant-start-time">{generalInfo.status === 0 ? startTime.format("HH:mm") : Site.MappingStatusMatchText(generalInfo.status)}</div>
                    <div className="duel-participant-home">
                        <div className="participant-image">
                            <a>
                                <img width="58" height="58" alt={generalInfo.homeTeam.teamFullNameEN} loading="lazy"
                                    src={generalInfo.homeTeam.icon} />
                            </a>
                        </div>
                        <div className="participant-name-wrapper">
                            <div className="participant-name">{generalInfo.homeTeam.teamFullNameEN}</div>
                            {this.getBallAndCardNumber(IncidentPosition.Home)}
                        </div>
                    </div>
                    <div className="duel-participant-score">
                        {this.getMatchScores(generalInfo)}
                    </div>
                    <div className="duel-participant-away">
                        <div className="participant-image">
                            <a>
                                <img width="58" height="58" alt={generalInfo.awayTeam.teamFullNameEN} loading="lazy"
                                    src={generalInfo.awayTeam.icon} />
                            </a>
                        </div>
                        <div className="participant-name-wrapper">
                            <div className="participant-name">{generalInfo.awayTeam.teamFullNameEN}</div>
                            {this.getBallAndCardNumber(IncidentPosition.Away)}
                        </div>
                    </div>
                </div>
            </>
        );
    }
}
