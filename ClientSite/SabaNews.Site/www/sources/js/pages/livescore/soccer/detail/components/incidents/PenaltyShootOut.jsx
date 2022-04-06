import React, { Component } from 'react';
import  IncidentIcon from '@jsroot/components/Icon/IncidentIcon'

export default class PenaltyShootOut extends Component {
    constructor(props) {
        super(props);
    }
    
    render() {
        const {penaltyShootOutData} = this.props;
        return (
            <>
                <div className="item-dienbien flexbox">
                        <div className="inner-item-db flexbox item-th-home">
                            <div className="item-th flexbox">
                                <span className="icon-th">
                                    <IncidentIcon type = {penaltyShootOutData.homeType}/>
                                </span>
                                <span className="name-th">{penaltyShootOutData.homePlayerName} <span className="noted"></span></span>
                            </div>
                        </div>
                        <span className="item-mins flexbox">{penaltyShootOutData.score}</span>
                        <div className="inner-item-db flexbox">
                            <div className="item-th flexbox">
                                <span className="icon-th">
                                    <IncidentIcon type = {penaltyShootOutData.awayType}/>
                                </span>
                                <span className="name-th">{penaltyShootOutData.awayPlayerName} <span className="noted"></span></span>
                            </div>
                        </div>
                    </div>
            </>
        );
    }
}
