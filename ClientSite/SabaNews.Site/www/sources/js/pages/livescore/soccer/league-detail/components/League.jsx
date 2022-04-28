import React from 'react';
import TeamIcon from '@jsroot/components/Icon/TeamIcon';
import CommonFunctions from '@jsroot/common/common-functions';
import FavoriteMatches from '@jsroot/components/FavoriteMatches';

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
                <FavoriteMatches isMobile />
            </div>
        </div>
    );
};

export default League;