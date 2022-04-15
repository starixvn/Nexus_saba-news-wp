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
            <div className="item-dienbien flexbox">
                <div className="inner-item-db flexbox item-th-home">
                    {
                        incident.position === IncidentPosition.Home &&
                        <>
                            <div className="item-th flexbox">
                                <span className="icon-th">
                                    <svg className="ic ic-arrow arrowupgreen" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                        <path d="M34.9 289.5l-22.2-22.2c-9.4-9.4-9.4-24.6 0-33.9L207 39c9.4-9.4 24.6-9.4 33.9 0l194.3 194.3c9.4 9.4 9.4 24.6 0 33.9L413 289.4c-9.5 9.5-25 9.3-34.3-.4L264 168.6V456c0 13.3-10.7 24-24 24h-32c-13.3 0-24-10.7-24-24V168.6L69.2 289.1c-9.3 9.8-24.8 10-34.3.4z"/>
                                    </svg>
                                </span>
                                <span className="name-th">{incident.inPlayerName} <span className="noted"></span></span>
                            </div>
                            <div className="item-th flexbox">
                                <span className="icon-th">
                                    <svg className="ic ic-arrow arrowdownred" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                                        <path d="M374.6 310.6l-160 160C208.4 476.9 200.2 480 192 480s-16.38-3.125-22.62-9.375l-160-160c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L160 370.8V64c0-17.69 14.33-31.1 31.1-31.1S224 46.31 224 64v306.8l105.4-105.4c12.5-12.5 32.75-12.5 45.25 0S387.1 298.1 374.6 310.6z"/>
                                    </svg>
                                </span>
                                <span className="name-th">{incident.outPlayerName}<span className="noted"></span></span>
                            </div>
                        </>
                    }
                </div>
                <span className="item-mins flexbox">{incident.timeShow + "'"}</span>
                <div className="inner-item-db flexbox">
                    {
                        incident.position === IncidentPosition.Away &&
                        <>
                            <div className="item-th flexbox">
                                <span className="icon-th">
                                    <svg className="ic ic-arrow arrowupgreen" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                        <path d="M34.9 289.5l-22.2-22.2c-9.4-9.4-9.4-24.6 0-33.9L207 39c9.4-9.4 24.6-9.4 33.9 0l194.3 194.3c9.4 9.4 9.4 24.6 0 33.9L413 289.4c-9.5 9.5-25 9.3-34.3-.4L264 168.6V456c0 13.3-10.7 24-24 24h-32c-13.3 0-24-10.7-24-24V168.6L69.2 289.1c-9.3 9.8-24.8 10-34.3.4z"/>
                                    </svg>
                                </span>
                                <span className="name-th">{incident.inPlayerName} <span className="noted"></span></span>
                            </div>
                            <div className="item-th flexbox">
                                <span className="icon-th">
                                    <svg className="ic ic-arrow arrowdownred" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                                        <path d="M374.6 310.6l-160 160C208.4 476.9 200.2 480 192 480s-16.38-3.125-22.62-9.375l-160-160c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L160 370.8V64c0-17.69 14.33-31.1 31.1-31.1S224 46.31 224 64v306.8l105.4-105.4c12.5-12.5 32.75-12.5 45.25 0S387.1 298.1 374.6 310.6z"/>
                                    </svg>
                                </span>
                                <span className="name-th">{incident.outPlayerName}<span className="noted"></span></span>
                            </div>
                        </>
                    }
                </div>
            </div>
        )
    }

    renderGeneral = () =>  {
        const { incident } = this.props;
        const processScore = "[" + incident.homeScore + " - " + incident.awayScore + "]";
        return (
                <div className="item-dienbien flexbox">
                    <div className="inner-item-db flexbox item-th-home">
                        {
                            incident.position === IncidentPosition.Home &&
                            <div className="item-th flexbox">
                                {
                                    Site.CheckIncidentTypeToShowScore(incident.type) &&
                                    <span className="name-th">({processScore}) <span className="noted"></span></span>
                                }
                                <span className="icon-th">
                                    <IncidentIcon type = {incident.type}/>
                                </span>
                                <span className="name-th">{ incident.varResult === 0 ?  incident.playerName : Site.MappingVarResultText(incident.varResult)} <span className="noted"></span></span>
                            </div>
                        }
                    </div>
                    <span className="item-mins flexbox">{incident.timeShow + "'"}</span>
                    <div className="inner-item-db flexbox">
                        {
                            incident.position === IncidentPosition.Away &&
                            <div className="item-th flexbox">
                                <span className="icon-th">
                                    <IncidentIcon type = {incident.type}/>
                                </span>
                                <span className="name-th">{ incident.varResult === 0 ?  incident.playerName : Site.MappingVarResultText(incident.varResult)} <span className="noted"></span></span>
                                {
                                    Site.CheckIncidentTypeToShowScore(incident.type) &&
                                    <span className="name-th">({processScore}) <span className="noted"></span></span>
                                }
                            </div>
                        }
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
