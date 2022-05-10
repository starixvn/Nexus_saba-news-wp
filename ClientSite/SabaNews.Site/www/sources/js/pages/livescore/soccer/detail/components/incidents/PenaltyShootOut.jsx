import React, { PureComponent } from 'react';
import IncidentIcon from '@jsroot/components/Icon/IncidentIcon';
import { IncidentIconType } from '@jsroot/common/constants';

export default class PenaltyShootOut extends PureComponent {
    render() {
        const { data } = this.props;
        const homeType = data.homeType === IncidentIconType.Penalty ? IncidentIconType.PenaltyShootOutSuccess : data.homeType;
        const awayType = data.awayType === IncidentIconType.Penalty ? IncidentIconType.PenaltyShootOutSuccess : data.awayType;
        return (
            <div className="line-up-live-item flexbox">
                <div className="inner-item-db flexbox item-th-home">
                    <div className="item-th flexbox">
                        <span className="icon-th in-out-icon">
                            <IncidentIcon type={homeType} />
                        </span>
                        <span className="name-th">{data.homePlayerName} <span className="noted" /></span>
                    </div>
                </div>
                <span className="item-mins flexbox">{data.score}</span>
                <div className="inner-item-db flexbox item-th-away">
                    <div className="item-th flexbox">
                        <span className="name-th">{data.awayPlayerName} <span className="noted" /></span>
                        <span className="icon-th in-out-icon">
                            <IncidentIcon type={awayType} />
                        </span>
                    </div>
                </div>
            </div>
        );
    }
}