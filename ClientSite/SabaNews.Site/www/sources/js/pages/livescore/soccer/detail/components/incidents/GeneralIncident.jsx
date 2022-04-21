import React, { Component } from 'react';
import { IncidentPosition, IncidentStatus } from '@jsroot/common/constants';
import Site from '@jsroot/common/site';
import  IncidentIcon from '@jsroot/components/Icon/IncidentIcon'
export default class GeneralIncident extends Component {
    constructor(props) {
        super(props);
    }
    renderSubsitution = () => {
        const { incident } = this.props;
        return (
            <div className="line-up-live">
                <div className="line-up-live-item flexbox">
                    <div className="inner-item-db flexbox item-th-home">
                        {
                            incident.position === IncidentPosition.Home &&
                            <>
                                <div className="item-th flexbox">
                                    <i className="in-out-icon icon-livescore icon-in"/>
                                    <span className="name-th">{incident.inPlayerName} <span className="noted"></span></span>
                                </div>
                                <div className="item-th flexbox">
                                    <i className="in-out-icon icon-livescore icon-out" />
                                    <span className="name-th">{incident.outPlayerName}<span className="noted"></span></span>
                                </div>
                            </>
                        }
                    </div>
                    <span className="item-mins flexbox">{incident.timeShow + "'"}</span>
                    <div className="inner-item-db flexbox item-th-away">
                        {
                            incident.position === IncidentPosition.Away &&
                            <>
                                <div className="item-th flexbox">
                                    <span className="name-th">{incident.inPlayerName} <span className="noted"></span></span>
                                    <i className="in-out-icon icon-livescore icon-in" />
                                </div>
                                <div className="item-th flexbox">
                                    <span className="name-th">{incident.outPlayerName}<span className="noted"></span></span>
                                    <i className="in-out-icon icon-livescore icon-out" />
                                </div>
                            </>
                        }
                    </div>
                </div>
            </div>
        )
    }

    renderGeneral = () =>  {
        const { incident } = this.props;
        const processScore = "[" + incident.homeScore + " - " + incident.awayScore + "]";
        return (
            <div className="line-up-live">
                <div className="line-up-live-item flexbox">
                    <div className="inner-item-db flexbox item-th-home">
                        {
                            incident.position === IncidentPosition.Home &&
                            <ul className="item-th flexbox">
                                <li className="item-th-time">{incident.timeShow + "'"}</li>
                                <li>
                                    <span className="item-th-name">{incident.varResult === 0 ? incident.playerName : Site.MappingVarResultText(incident.varResult)} <span className="noted"></span></span>
                                </li>
                                
                            </ul>
                        }
                    </div>
                    <ul className="item-mins flexbox">
                        <li>
                            {
                                Site.CheckIncidentTypeToShowScore(incident.type) &&
                                <span className="name-th">({processScore}) <span className="noted"></span></span>
                            }
                        </li>
                        <li className="icon-th">
                            <IncidentIcon type={incident.type} />
                        </li>
                    </ul>
                    <div className="inner-item-db flexbox item-th-away">
                        {
                            incident.position === IncidentPosition.Away &&
                            <ul className="item-th flexbox">
                                <li className="item-th-time">{incident.timeShow + "'"}</li>
                                <li>
                                    <span className="item-th-name">{incident.varResult === 0 ? incident.playerName : Site.MappingVarResultText(incident.varResult)} <span className="noted"></span></span>
                                </li>
                            </ul>
                        }
                    </div>
                </div>
            </div>
                
        )
    }
    render() {
        if (!this.props.incident) {
            return null;
        }
        const { incident } = this.props;
        let html = null;
        if(incident.type === IncidentStatus.Substitution) {
            html = this.renderSubsitution();
        } else {
            html = this.renderGeneral();
        }
        return (
            <>
                {html}
            </>
        );
    }
}
