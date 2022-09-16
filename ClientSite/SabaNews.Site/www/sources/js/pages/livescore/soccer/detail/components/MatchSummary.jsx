import React, { Component, Fragment } from 'react';
import { IncidentPosition, IncidentStatus, MatchStatus } from '@jsroot/common/constants';
import GeneralIncident from './incidents/GeneralIncident';
import PenaltyShootOut from './incidents/PenaltyShootOut';
import CommonFunctions from '@jsroot/common/common-functions';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import LangHelper from '@jsroot/common/langHelper';

export class MatchSummary extends Component {
    checkIncidentMainTimeEventType = type => {
        return type === IncidentStatus.End
            || type === IncidentStatus.ExtraTimeEnd
            || type === IncidentStatus.Halftime;
    };


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
                return LangHelper.getResource('PenaltyShootoutEnd');
            default:
                return '';
        }
    };

    renderFinishStatus = () => {
        const { generalInfo } = this.props.match;
        const homeFinalScore = generalInfo.homeTeamExtraScore ? generalInfo.homeTeamExtraScore : generalInfo.homeTeamTotalScore;
        const awayFinalScore = generalInfo.awayTeamExtraScore ? generalInfo.awayTeamExtraScore : generalInfo.awayTeamTotalScore;
        const homeFinalPenalty = generalInfo.homeTeamPenaltyScore;
        const awayFinalPenalty = generalInfo.awayTeamPenaltyScore;
        const finalScore = `${homeFinalScore} -  ${awayFinalScore}`;
        const finalPenalty = `${homeFinalPenalty} -  ${awayFinalPenalty}`;
        return (
            <div className="head-top-team" >
                <div className="flexbox time-event">
                    <p className="time-event-title">Kết thúc trận đấu</p>
                </div>
                <div className="flexbox time-event">
                    <p className="time-event-score">{(homeFinalPenalty !== 0 || awayFinalPenalty !== 0) ? 'PEN: ' + finalPenalty : finalScore}</p>
                </div>
            </div>
        );
    };

    getPenaltyShootout = (incident, penaltyShootOutData, homePenaltyShootoutScore, awayPenaltyShootoutScore) => {
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
        }
        if (incident.position === IncidentPosition.Away && incident.type === IncidentStatus.Penalty) {
            awayPenaltyShootoutScore++;
        }
        return {penaltyShootOutData: penaltyShootOutData,homePenaltyShootoutScore: homePenaltyShootoutScore,awayPenaltyShootoutScore: awayPenaltyShootoutScore};
    };

    checkRenderPenaltyShootout = (penaltyShootOutData, hasPenaltyShootout) => {
        return hasPenaltyShootout && penaltyShootOutData !== null
            && penaltyShootOutData.awayType !== null && penaltyShootOutData.homeType !== null;
    };

    getMainTimeScore = (type, score) => {
        const { generalInfo } = this.props.match;
        if (type === IncidentStatus.Halftime) {
            score = `${generalInfo.homeTeamHalfScore} -  ${ generalInfo.awayTeamHalfScore}`;
        } else if (type === IncidentStatus.End) {
            score = `${generalInfo.homeTeamTotalScore} -  ${ generalInfo.awayTeamTotalScore}`;
        }
        return score;
    };

    checkRenderMainTimeEvent = (incident, lastIncident) => {
        return this.checkIncidentMainTimeEventType(incident.type)
            && (lastIncident.type !== IncidentStatus.End || incident.type !== IncidentStatus.End);
    };

    renderPenaltyShootout = incidents => {
        const Pen = incidents.filter(item => item.isPenaltyShootout === true);
        if(Pen.length === 0) {
            return null;
        }
        const homePen = Pen.filter(item => item.position === IncidentPosition.Home);
        const awayPen = Pen.filter(item => item.position === IncidentPosition.Away);
        let homeScore = 0;
        let awayScore = 0;
        return (
            <>
                {
                    homePen.map((hPen, index) => {
                        let component = <Fragment key={index} />;
                        const aPen = awayPen[index];
                        if(aPen) {
                            if (hPen.position === IncidentPosition.Home && hPen.type === IncidentStatus.Penalty) {
                                homeScore++;
                            }
                            if (aPen.position === IncidentPosition.Away && aPen.type === IncidentStatus.Penalty) {
                                awayScore++;
                            }
                            const penaltyShootOutData = {
                                homeType: hPen.type,
                                homePlayerName: hPen.playerName,
                                awayType: aPen.type,
                                awayPlayerName: aPen.playerName,
                                score: `${homeScore} -  ${awayScore}`
                            };
                            component = <PenaltyShootOut key={index} penaltyShootOutData={penaltyShootOutData} />;
                        }
                        return component;
                    })
                }
            </>
        );
    };



    render() {
        const { match } = this.props;
        if (!match.incidents || match.incidents.length === 0 || CommonFunctions.isPreMatch(match.generalInfo.status)) {
            return null;
        }

        const { generalInfo } = this.props.match;
        const incidents = CommonFunctions.produceIncidents(this.props.match.incidents);
        const lastIncident = incidents[incidents.length - 1];
        const mainIncidents = incidents.filter(item => !item.isPenaltyShootout);
        let homePenaltyShootoutScore = 0;
        let awayPenaltyShootoutScore = 0;
        let hasPenaltyShootout = false;
        let penaltyShootOutData = {
            homeType: null,
            homePlayerName: null,
            awayType: null,
            awayPlayerName: null,
            score: null
        };
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
                                    mainIncidents.map((incident, key) => {
                                        hasPenaltyShootout = false;
                                        const processScore = `${incident.homeScore} - ${incident.awayScore}`;
                                        if ((incident.type === IncidentStatus.Penalty || incident.type === IncidentStatus.PenaltyMissed)
                                            && (incident.homeScore === 0 && incident.awayScore === 0)) {
                                            hasPenaltyShootout = true;
                                            const shootoutResult = this.getPenaltyShootout(incident, penaltyShootOutData, homePenaltyShootoutScore, awayPenaltyShootoutScore);
                                            penaltyShootOutData = shootoutResult.penaltyShootOutData;
                                            homePenaltyShootoutScore = shootoutResult.homePenaltyShootoutScore;
                                            awayPenaltyShootoutScore = shootoutResult.awayPenaltyShootoutScore;
                                        }

                                        if (CommonFunctions.checkIncidentGeneralType(incident.type) && !hasPenaltyShootout) {
                                            return <GeneralIncident key={key} incident={incident} />;
                                        } else if (this.checkRenderMainTimeEvent(incident, lastIncident)) {
                                            const score = this.getMainTimeScore(incident.type, processScore);
                                            return (
                                                <div className="head-top-team" key={key}>
                                                    <div className="flexbox time-event">
                                                        <p className="time-event-title">{this.mappingIncidentTimeEventType(incident.type)}</p>
                                                    </div>
                                                    <div className="flexbox time-event">
                                                        <p className="time-event-score">{score}</p>
                                                    </div>
                                                </div>
                                            );
                                        } else if (this.checkRenderPenaltyShootout(penaltyShootOutData, hasPenaltyShootout)) {
                                            penaltyShootOutData.score = `${homePenaltyShootoutScore} -  ${awayPenaltyShootoutScore}`;
                                            hasPenaltyShootout = false;
                                            const penaltyShootOutComponent = <PenaltyShootOut key={key} penaltyShootOutData={penaltyShootOutData} />;
                                            penaltyShootOutData = {
                                                homeType: null,
                                                homePlayerName: null,
                                                awayType: null,
                                                awayPlayerName: null,
                                                score: null
                                            };
                                            return penaltyShootOutComponent;
                                        } else {
                                            return <Fragment key={key} />;
                                        }
                                    })
                                }
                                {this.renderPenaltyShootout(incidents)}
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
    return { match };
};

export default connect(mapStateToProps, mapDispatchToProps)(MatchSummary);