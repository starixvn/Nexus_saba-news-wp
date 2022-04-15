import React from 'react';
import CommonFunctions from '@jsroot/common/common-functions';

const League = ({ league }) => {
    return (
        <div className="livescore-league-header-detail">
            <div className="livescore-league-header-detail-logo"><img loading="lazy" src={league.icon} /></div>
            <div className="livescore-league-header-detail-name">{CommonFunctions.getLeagueName(league)}  <i className="icon-livescore icon-live-start start-active" /></div>

        </div>
    );
};

export default League;