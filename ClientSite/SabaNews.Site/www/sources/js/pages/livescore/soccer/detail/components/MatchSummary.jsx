import React, { Component } from 'react';
import { IncidentPosition, IncidentStatus, MatchStatus } from '@jsroot/common/constants';
import GeneralIncident from './incidents/GeneralIncident';
import PenaltyShootOut from './incidents/PenaltyShootOut';
import CommonFunctions from '@jsroot/common/common-functions';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import LangHelper from '@jsroot/common/langHelper';

export class MatchSummary extends Component {
    constructor(props) {
        super(props);
    }

    checkIncidentGeneralType = type => {
        if (type === IncidentStatus.Goal || type === IncidentStatus.YellowCard || type === IncidentStatus.RedCard || type === IncidentStatus.YellowTurnRedCard || type === IncidentStatus.OvertimeEnds
            || type === IncidentStatus.PenaltyMissed || type === IncidentStatus.OwnGoal || type === IncidentStatus.VAR || type === IncidentStatus.Penalty || type === IncidentStatus.Substitution) {
            return true;
        }
        return false;
    }

    checkIncidentMainTimeEventType = type => {
        if (type === IncidentStatus.End || type === IncidentStatus.OvertimeEnds
            || type === IncidentStatus.PenaltyShootoutEnd || type === IncidentStatus.Halftime) {
            return true;
        }
        return false;
    }


    mappingIncidentTimeEventType = type => {
        switch (type) {
            case IncidentStatus.GameStart:
                return LangHelper.getResource('GameStart');
            case IncidentStatus.Halftime:
                return LangHelper.getResource('HalftimeEnd');
            case IncidentStatus.End:
                return LangHelper.getResource('FullTimeEnd');
            case IncidentStatus.ExtraTimeEnd:
                return LangHelper.getResource('ExtraTimeEnd');
            case IncidentStatus.PenaltyShootoutEnd:
                return LangHelper.getResource('PenaltyShootoutEnd')
        }
    }


    renderFinishStatus = () => {
        const { generalInfo } = this.props.match;
        const homeFinalScore = generalInfo.homeTeamExtraScore ? generalInfo.homeTeamExtraScore : generalInfo.homeTeamTotalScore;
        const awayFinalScore = generalInfo.awayTeamExtraScore ? generalInfo.awayTeamExtraScore : generalInfo.awayTeamTotalScore;
        const homeFinalPenalty = generalInfo.homeTeamPenaltyScore;
        const awayFinalPenalty = generalInfo.awayTeamPenaltyScore;
        const finalScore = homeFinalScore + " - " + awayFinalScore;
        const finalPenalty = homeFinalPenalty + " - " + awayFinalPenalty;
        return (
            <div className="head-top-team" >
                <div className="flexbox time-event">
                    <p className="time-event-title">Trận Đấu kết thúc</p>
                </div>
                <div className="flexbox time-event">
                    <p className="time-event-score">{(homeFinalPenalty !== 0 || awayFinalPenalty !== 0) ? "PEN: " + finalPenalty : finalScore}</p>
                </div>
            </div>
        )
    }

    render() {
        const { match } = this.props;
        if (!match.incidents || match.incidents.length === 0 || CommonFunctions.checkPreMatchStatus(match.generalInfo.status)) {
            return null;
        }
        const { generalInfo } = this.props.match;
        const incidents = CommonFunctions.produceIncidents(this.props.match.incidents);
        let lastIncident = incidents[incidents.length - 1];
        let hasPenaltyShootout = false;
        let homePenaltyShootoutScore = 0;
        let awayPenaltyShootoutScore = 0;
        let penaltyShootoutScore = "0 - 0";
        let processScore = "0 - 0";
        let penaltyShootOutData = {
            homeType: null,
            homePlayerName: null,
            awayType: null,
            awayPlayerName: null,
            score: null
        }
        return (
            <div className="block-line-up" id="dien-bien">
                <div className="block-line-up-main">
                    <div className="block-line-up-title">Diễn Biến</div>
                    <div className="block-line-up-body">
                        <div className="time-line">
                            <div className="time-line-start">
                                <p className="icon-start"><i className="icon-livescore icon-mdi_whistle" /></p>
                                <p className="txt-start">Bắt đầu</p>
                            </div>
                            <div className="time-line-main">
                                {
                                    incidents.map((incident, key) => {
                                        processScore = incident.homeScore + " - " + incident.awayScore;
                                        if ((incident.type === IncidentStatus.Penalty || incident.type === IncidentStatus.PenaltyMissed) && (incident.homeScore === 0 && incident.awayScore === 0)) {
                                            hasPenaltyShootout = true;
                                            if (incident.position === IncidentPosition.Home) {
                                                penaltyShootOutData.homeType = incident.type;
                                                penaltyShootOutData.homePlayerName = incident.playerName;
                                            }
                                            if (incident.position === IncidentPosition.Away) {
                                                penaltyShootOutData.awayType = incident.type;
                                                penaltyShootOutData.awayPlayerName = incident.playerName;
                                            }
                                            if (incident.position === IncidentPosition.Home && incident.type === IncidentStatus.Penalty) {
                                                homePenaltyShootoutScore++;
                                                penaltyShootoutScore = homePenaltyShootoutScore + " - " + awayPenaltyShootoutScore;
                                            }
                                            if (incident.position === IncidentPosition.Away && incident.type === IncidentStatus.Penalty) {
                                                awayPenaltyShootoutScore++;
                                                penaltyShootoutScore = homePenaltyShootoutScore + " - " + awayPenaltyShootoutScore;
                                            }
                                        }
                                        if (this.checkIncidentGeneralType(incident.type) && !hasPenaltyShootout) {
                                            return (
                                                <GeneralIncident
                                                    key={key}
                                                    incident={incident}
                                                />
                                            )
                                        } else if (this.checkIncidentMainTimeEventType(incident.type) && (lastIncident.type !== IncidentStatus.End || incident.type !== IncidentStatus.End)) {
                                            let score = processScore;
                                            if (incident.type === IncidentStatus.Halftime) {
                                                score = generalInfo.homeTeamHalfScore + " - " + generalInfo.awayTeamHalfScore;
                                            } else if (incident.type === IncidentStatus.End) {
                                                score = generalInfo.homeTeamTotalScore + " - " + generalInfo.awayTeamTotalScore;
                                            }
                                            return (
                                                <div className="head-top-team" key={key}>
                                                    <div className="flexbox time-event">
                                                        <p className="time-event-title">{this.mappingIncidentTimeEventType(incident.type)}</p>
                                                    </div>
                                                    <div className="flexbox time-event">
                                                        <p className="time-event-score">{score}</p>
                                                    </div>
                                                </div>
                                            )
                                        } else if (hasPenaltyShootout && penaltyShootOutData !== null && penaltyShootOutData.awayType !== null && penaltyShootOutData.homeType !== null) {
                                            penaltyShootOutData.score = penaltyShootoutScore;
                                            hasPenaltyShootout = false;
                                            let component = <PenaltyShootOut key={key} penaltyShootOutData={penaltyShootOutData} />
                                            penaltyShootOutData = {
                                                homeType: null,
                                                homePlayerName: null,
                                                awayType: null,
                                                awayPlayerName: null,
                                                score: null
                                            };
                                            return (component)
                                        }
                                    })
                                }
                                {
                                    generalInfo.status === MatchStatus.Finished &&
                                    this.renderFinishStatus()
                                }
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { match } = state;
    return {
        match
    };
};

export default connect(mapStateToProps, mapDispatchToProps)(MatchSummary);