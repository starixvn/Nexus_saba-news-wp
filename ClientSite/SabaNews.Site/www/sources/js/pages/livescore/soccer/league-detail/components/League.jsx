import React from 'react';
import TeamIcon from '@jsroot/components/Icon/TeamIcon';
import CommonFunctions from '@jsroot/common/common-functions';

const League = ({ league }) => {
    return (
        <div className="livescore-league-header-detail">
            <div className="livescore-league-header-detail-group">
                <div className="livescore-league-header-detail-logo"><TeamIcon src={league.icon} /></div>
                <div className="livescore-league-header-detail-name">
                    {CommonFunctions.getLeagueName(league)} <i className="icon-livescore icon-live-start start-active" />
                </div>
            </div>
            <div className="group-searchbar">
                <div className="livescore-like">
                    <span>Trận đấu quan tâm</span>
                    <div className="count-start">
                        <i className="icon-livescore icon-live-start" data-badge="1"></i>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default League;