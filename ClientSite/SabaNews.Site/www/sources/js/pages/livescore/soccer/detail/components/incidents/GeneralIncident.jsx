import React, { Component } from 'react';
import { IncidentPosition } from '@jsroot/common/constants';
import Site from '@jsroot/common/site';
import  IncidentIcon from '@jsroot/components/Icon/IncidentIcon'
export default class GeneralIncident extends Component {
    constructor(props) {
        super(props);
    }
    
    render() {
        if (!this.props.incident) {
            return null;
        }
        const {incident,hasInjuryTime, processScore} = this.props;
        let time = incident.time + "'";
        if(hasInjuryTime) {
            if(incident.time >= 45 && incident.time < 90) {
                time = "45" + "+" + (incident.time - 45) + "'"; 
            } else {
                time = "90" + "+" + (incident.time - 90) + "'"; 
            }
        }
        return (
            <>
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
                        <span className="item-mins flexbox">{time}</span>
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
            </>
        );
    }
}
