import React from 'react';
import CommonFunctions from '@jsroot/common/common-functions';

const League = ({ league }) => {
    return (
        <div className="livescore-league-detail-header">
            <div className="livescore-league-logo"><img loading="lazy" src={league.icon} /></div>
            <div className="livescore-league-name">{CommonFunctions.getLeagueName(league)}</div>
            <i className="icon-livescore icon-live-start start-active" />
        </div>
    );
};

export default League;