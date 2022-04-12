import React, { Component } from 'react';
import Site from '@jsroot/common/site';
import { MatchStatus, IncidentStatus, IncidentPosition } from '@jsroot/common/constants';
import  IncidentIcon from '@jsroot/components/Icon/IncidentIcon'
import DateTimeService from '@jsroot/common/dateTimeService';

export default class MatchGenetalInfo extends Component {
    constructor(props) {
        super(props);
    }
    getMatchScores = (match, incidents) => {
        if(!incidents) {
            return (<div className="detail-score-wrapper live">
                        <span>VS</span>
                    </div>);
        }
        const numberHomePenaltyShootOut = incidents.filter(item => item.type ===  IncidentStatus.Penalty && item.position === 1 && (item.homeScore === 0 && item.awayScore === 0)).length;
        const numberAwayPenaltyShootOut = incidents.filter(item => item.type ===  IncidentStatus.Penalty && item.position === 2 && (item.homeScore === 0 && item.awayScore === 0)).length;
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

    getBallAndCardNumber = (incidents, position) => {
        if(!incidents) {
            return null;
        }
        let goalsStatusSelected = [IncidentStatus.Goal, IncidentStatus.Penalty, IncidentStatus.OwnGoal].join(',');
        const goals = incidents.filter(item => goalsStatusSelected.includes(item.type.toString())  && item.position === position && (item.homeScore !== 0 || item.awayScore !== 0));
        const numberRedCard = incidents.filter(item => (item.type ===  IncidentStatus.RedCard || item.type === IncidentStatus.YellowTurnRedCard) && item.position === position).length;
        const numberYellowCard = incidents.filter(item => item.type ===  IncidentStatus.YellowCard && item.position === position).length;
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
        const { generalInfo, incidents } = this.props.match;
        const startTime = DateTimeService.convertFromUTC(generalInfo.startTime);
        return (
            <>
                <div className="tournament-header">{generalInfo.league.leagueFullNameEN}
                - {startTime.format("DD/MM/YYYY")}
                { !generalInfo.league.currentRound ? " - Vòng " + generalInfo.league.currentRound : "" }
                </div>

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
                            {this.getBallAndCardNumber(incidents, IncidentPosition.Home)}
                        </div>
                    </div>
                    <div className="duel-participant-score">
                        {this.getMatchScores(generalInfo, incidents)}
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
                            {this.getBallAndCardNumber(incidents, IncidentPosition.Away)}
                        </div>
                    </div>
                </div>
            </>
        );
    }
}
