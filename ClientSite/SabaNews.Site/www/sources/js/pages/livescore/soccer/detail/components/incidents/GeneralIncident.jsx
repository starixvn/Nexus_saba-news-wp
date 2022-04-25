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
                <div className="line-up-live-item flexbox ">
                    <div className="inner-item-db flexbox item-th-home">
                        {
                            incident.position === IncidentPosition.Home &&
                            <>
                                <div className="item-th-time">{incident.timeShow + "'"}</div>
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
                    <div className="item-mins flexbox">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.7258 3.94819L20.4738 1.2002H10.8738V10.8002L13.6338 8.04019C15.9978 9.79219 17.5338 12.5762 17.5338 15.7442C17.5338 17.3162 17.1498 18.7922 16.4778 20.1002C19.2738 18.2762 21.1338 15.1322 21.1338 11.5442C21.1338 8.52019 19.8018 5.82019 17.7258 3.94819Z" fill="#03A700" />
                            <path d="M6.62588 8.25639C6.62588 6.68439 7.00988 5.20839 7.68188 3.90039C4.87388 5.72439 3.02588 8.86839 3.02588 12.4564C3.02588 15.4804 4.34588 18.1804 6.43388 20.0524L3.67388 22.8004H13.2739V13.2004L10.5139 15.9604C8.16188 14.2084 6.62588 11.4244 6.62588 8.25639Z" fill="#FF5744" />
                        </svg>
                    </div>
                    <div className="inner-item-db flexbox item-th-away">
                        {
                            incident.position === IncidentPosition.Away &&
                            <>
                                <div className="item-th-time">{incident.timeShow + "'"}</div>
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
        const processScore = incident.homeScore + " - " + incident.awayScore;
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
                                {
                                    (incident.type === IncidentStatus.Goal && incident.assistName) &&
                                    <li className="sp-score">
                                        <i className="icon-livescore icon-shoes" /> <span className="item-th-name">{incident.assistName} <span className="noted"></span></span>
                                    </li>
                                }
                            </ul>
                        }
                    </div>
                    <ul className="item-mins flexbox">
                        <li>
                            {
                                Site.CheckIncidentTypeToShowScore(incident.type) &&
                                <span className="name-th">{processScore} <span className="noted"></span></span>
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
                                {
                                    (incident.type === IncidentStatus.Goal && incident.assistName) &&
                                    <li className="sp-score">
                                        <span className="item-th-name">{incident.assistName} <span className="noted"></span></span> <i className="icon-livescore icon-shoes" />
                                    </li>
                                }
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
