import React from 'react';

const League = ({ league }) => {
    return (
        <div className="livescore-league-header-detail">
            <div className="livescore-league-header-detail-logo"><img loading="lazy" src={league.icon} /></div>
            <div className="livescore-league-header-detail-name">
                {league.leagueFullNameEN} <i className="icon-livescore icon-live-start start-active" />
            </div>
        </div>
    );
};

export default League;