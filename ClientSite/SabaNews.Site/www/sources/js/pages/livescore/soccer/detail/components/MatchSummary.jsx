import React, { Component } from 'react';
import { IncidentPosition, IncidentStatus } from '@jsroot/common/constants';
import Substitution from './incidents/Substitution';
import GeneralIncident from './incidents/GeneralIncident';
import PenaltyShootOut from './incidents/PenaltyShootOut';
import Site from '@jsroot/common/site';

export default class MatchSummary extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        if (!this.props.soccerMatch || !this.props.soccerMatch.incidents || this.props.soccerMatch.incidents.length === 0) {
            return null;
        }
        const incidents = this.props.soccerMatch.incidents;
        let lastIncident = incidents[incidents.length - 1];
        let hasInjuryTime = false;
        let hasPenaltyShootout = false;
        let homePenaltyShootoutScore = 0;
        let awayPenaltyShootoutScore = 0;
        let penaltyShootoutScore = "[0 - 0]";
        let halfTimeScore = "[0 - 0]";
        let processScore = "[0 - 0]";
        let penaltyShootOutData = {
            homeType: null,
            homePlayerName: null,
            awayType: null,
            awayPlayerName: null,
            score: null
        }
        return (
            <div className="block width_common block-detail-trandau">
                <div className="width_common box-dienbien">
                    <div className="head-top-team">
                        <div className="flexbox time-event">
                            <p>Bắt đầu</p>
                        </div>
                    </div>
                    {
                        incidents.map((incident, key) => {
                            if(Site.CheckIncidentTypeToUpdateScore(incident.type)) {
                                processScore = "[" + incident.homeScore + " - " + incident.awayScore + "]";
                            }
                            if(incident.type === IncidentStatus.InjuryTime && (incident.time >= 45 && incident.time < 105)) {
                                hasInjuryTime = true;
                            }
                            if(incident.type ===  IncidentStatus.Halftime) {
                                hasInjuryTime = false;
                                halfTimeScore = processScore;
                            }
                            if(incident.type ===  IncidentStatus.End) {
                                hasInjuryTime = false;
                            }
                            if((incident.type === IncidentStatus.Penalty || incident.type === IncidentStatus.PenaltyMissed) && (incident.homeScore === 0 && incident.awayScore === 0)) {
                                hasPenaltyShootout = true;
                                if(incident.position === IncidentPosition.Home) {
                                    penaltyShootOutData.homeType = incident.type;
                                    penaltyShootOutData.homePlayerName = incident.playerName;
                                }
                                if(incident.position === IncidentPosition.Away) {
                                    penaltyShootOutData.awayType = incident.type;
                                    penaltyShootOutData.awayPlayerName = incident.playerName;
                                }
                                if(incident.position === IncidentPosition.Home && incident.type === IncidentStatus.Penalty) {
                                    homePenaltyShootoutScore++;
                                    penaltyShootoutScore = "[" + homePenaltyShootoutScore + " - " + awayPenaltyShootoutScore + "]";
                                }
                                if(incident.position === IncidentPosition.Away && incident.type === IncidentStatus.Penalty) {
                                    awayPenaltyShootoutScore++;
                                    penaltyShootoutScore = "[" + homePenaltyShootoutScore + " - " + awayPenaltyShootoutScore + "]";
                                }
                            }

                            if(incident.type === IncidentStatus.Substitution) {
                                return (
                                    <Substitution
                                        key={key}
                                        incident = {incident}
                                        hasInjuryTime = {hasInjuryTime}
                                    />
                                )
                            } else if(Site.CheckIncidentGeneralType(incident.type) && !hasPenaltyShootout) {
                                return (
                                    <GeneralIncident
                                        key={key}
                                        incident = {incident}
                                        processScore = {processScore}
                                        hasInjuryTime = {hasInjuryTime}
                                    />
                                )
                            } else if(Site.CheckIncidentTimeEventType(incident.type) && (lastIncident.type !== IncidentStatus.End || incident.type !== IncidentStatus.End)) {
                                return (
                                    <div className="head-top-team" key={key}>
                                        <div className="flexbox time-event">
                                            <p>{Site.MappingIncidentTimeEventType(incident.type)}</p>
                                        </div>
                                        <div className="flexbox time-event">
                                            <p>{processScore}</p>
                                        </div>
                                    </div>
                                )
                            } else if (hasPenaltyShootout && penaltyShootOutData !== null && penaltyShootOutData.awayType !== null && penaltyShootOutData.homeType !== null) {
                                penaltyShootOutData.score = penaltyShootoutScore;
                                hasPenaltyShootout = false;
                                let component = <PenaltyShootOut key = {key} penaltyShootOutData = {penaltyShootOutData} />
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

                    <div className="head-top-team" >
                        <div className="flexbox time-event">
                            <p>Trận Đấu kết thúc</p>
                        </div>
                        <div className="flexbox time-event">
                            <p>{(homePenaltyShootoutScore !== 0 ||  homePenaltyShootoutScore !== 0)? "PEN: " + penaltyShootoutScore : processScore}</p>
                        </div>
                        </div>
                    </div>
            </div>
        );
    }
}