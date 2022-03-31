import React, { Component } from 'react';

import PostMatch from './PostMatch';
import PreMatch from './PreMatch';
import LiveMatch from './LiveMatch';
import moment from 'moment';
export default class MatchGenetalInfo extends Component {
    constructor(props) {
        super(props);
    }
    
    render() {
        if (!this.props.soccerMatch || !this.props.soccerMatch.generalInfo) {
            return null;
        }
        const soccerMatch = this.props.soccerMatch;
        const isLiveMatch = this.props.isLiveMatch
        const startTime = moment(soccerMatch.generalInfo.startTime);
        let matchComponent;
        if (isLiveMatch) {
            matchComponent = <LiveMatch />
        } else if (soccerMatch.generalInfo.status === 50) {
            matchComponent = <PostMatch />
        } else {
            matchComponent = <PreMatch />
        }
        return (
            <>
                <div className="tournament-header">{soccerMatch.generalInfo.leagueNameEN} 
                - {startTime.format("DD/MM/YYYY")} 
                { soccerMatch.generalInfo.round !== null ? " - Vòng " + soccerMatch.generalInfo.round : "" }
                </div>
                {matchComponent}
            </>
        );
    }
}
