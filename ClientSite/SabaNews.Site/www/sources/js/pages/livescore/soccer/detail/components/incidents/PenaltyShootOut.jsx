import React, { Component } from 'react';
import IncidentIcon from '@jsroot/components/Icon/IncidentIcon';
import { IncidentStatus } from '@jsroot/common/constants';

export default class PenaltyShootOut extends Component {
    constructor(props) {
        super(props);
    }
    render() {
        const { penaltyShootOutData } = this.props;
        const homeType = penaltyShootOutData.homeType === IncidentStatus.Penalty ? IncidentStatus.PenaltyShootOutSuccess : penaltyShootOutData.homeType;
        const awayType = penaltyShootOutData.awayType === IncidentStatus.Penalty ? IncidentStatus.PenaltyShootOutSuccess : penaltyShootOutData.awayType;
        return (
            <>
                <div className="line-up-live-item flexbox">
                    <div className="inner-item-db flexbox item-th-home">
                        <div className="item-th flexbox">
                            <span className="icon-th in-out-icon">
                                <IncidentIcon type={homeType} />
                            </span>
                            <span className="name-th">{penaltyShootOutData.homePlayerName} <span className="noted"></span></span>
                        </div>
                    </div>
                    <span className="item-mins flexbox">{penaltyShootOutData.score}</span>
                    <div className="inner-item-db flexbox item-th-away">
                        <div className="item-th flexbox">
                            <span className="name-th">{penaltyShootOutData.awayPlayerName} <span className="noted"></span></span>
                            <span className="icon-th in-out-icon">
                                <IncidentIcon type={awayType} />
                            </span>
                        </div>
                    </div>
                </div>
            </>
        );
    }
}
