import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import { MatchStatus, IncidentStatus } from '@jsroot/common/constants';
import  IncidentIcon from '@jsroot/components/Icon/IncidentIcon'

export class PostMatch extends Component {
    constructor(props) {
        super(props);
    }
    
    render() {
        if (!this.props.soccerMatch ) {
            return null;
        }
        const {generalInfo, incidents} = this.props.soccerMatch;
        let goalsStatusSelected = [IncidentStatus.Goal, IncidentStatus.Penalty, IncidentStatus.OwnGoal].join(',');
        const homeGoals = incidents.filter(item => goalsStatusSelected.includes(item.type.toString())  && item.position === 1 && (item.homeScore !== 0 || item.awayScore !== 0));
        const awayGoals = incidents.filter(item => goalsStatusSelected.includes(item.type.toString()) && item.position === 2 && (item.homeScore !== 0 || item.awayScore !== 0));
        const numberHomeRedCard = incidents.filter(item => (item.type ===  IncidentStatus.RedCard || item.type === IncidentStatus.YellowTurnRedCard) && item.position === 1).length;
        const numberHomeYellowCard = incidents.filter(item => item.type ===  IncidentStatus.YellowCard && item.position === 1).length;
        const numberHomePenaltyShootOut = incidents.filter(item => item.type ===  IncidentStatus.Penalty && item.position === 1 && (item.homeScore === 0 && item.awayScore === 0)).length;
        
        const numberAwayRedCard = incidents.filter(item => (item.type ===  IncidentStatus.RedCard || item.type === IncidentStatus.YellowTurnRedCard) && item.position === 2).length;
        const numberAwayYellowCard = incidents.filter(item => item.type ===  IncidentStatus.YellowCard && item.position === 2).length;
        const numberAwayPenaltyShootOut = incidents.filter(item => item.type ===  IncidentStatus.Penalty && item.position === 2 && (item.homeScore === 0 && item.awayScore === 0)).length;
        return (
            <>
                <div className="duel-participant">
                    <div className="duel-participant-start-time">Kết thúc</div>
                    <div className="duel-participant-home">
                        <div className="participant-image">
                            <a>
                                <img width="58" height="58" alt={generalInfo.homeTeamNameEN} loading="lazy"
                                    src={generalInfo.homeTeamIcon} />
                            </a>
                        </div>
                        <div className="participant-name-wrapper">
                            <div className="participant-name">{generalInfo.homeTeamNameEN}</div>
                        </div>
                       
                        <div className="participant-detail">
                            {
                                homeGoals.map( (goal, gkey) => {
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
                                (numberHomeRedCard !== 0 || numberHomeYellowCard !== 0) &&
                                <>
                                    <span className="red-card">{numberHomeRedCard}</span>
                                    <span className="yellow-card">{numberHomeYellowCard}</span>
                                </>
                            }
                            
                        </div>
                    </div>
                    <div className="duel-participant-score">
                        <div className="detail-score-match-info live">
                            <div className="detail-score-wrapper live">
                                <span>{generalInfo.homeTeamTotalScore}</span>
                                <span className="detail-score-divider">-</span>
                                <span>{generalInfo.awayTeamTotalScore}</span>
                            </div>
                            <div className="detail-score-status">
                                <p>H1: {generalInfo.homeTeamHalfScore} - {generalInfo.awayTeamHalfScore}</p>
                                {
                                    (numberHomePenaltyShootOut !== 0 || numberAwayPenaltyShootOut !== 0) &&
                                    <p>Pen: {numberHomePenaltyShootOut} - {numberAwayPenaltyShootOut}</p>
                                }
                                
                            </div>
                        </div>
                    </div>
                    <div className="duel-participant-away">
                        <div className="participant-image">
                            <a>
                                <img width="58" height="58" alt={generalInfo.awayTeamNameEN} loading="lazy"
                                    src={generalInfo.awayTeamIcon} />
                            </a>
                        </div>
                        <div className="participant-name-wrapper">
                            <div className="participant-name">{generalInfo.awayTeamNameEN}</div>
                        </div>
                        <div className="participant-detail">
                        {
                                awayGoals.map((goal, gkey) => {
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
                                (numberAwayRedCard !== 0 || numberAwayYellowCard !== 0) &&
                                <>
                                    <span className="red-card">{numberAwayRedCard}</span>
                                    <span className="yellow-card">{numberAwayYellowCard}</span>
                                </>
                            }
                            
                        </div>
                    </div>
                </div>
            </>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { soccerMatch } = state;
    return {
        soccerMatch
    };
};

export default connect(mapStateToProps, mapDispatchToProps)(PostMatch);