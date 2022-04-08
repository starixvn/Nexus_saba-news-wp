import React from 'react';
import Match from './Match';

const League = ({ league, searchKey }) => {
    const reg = new RegExp(`(${searchKey})`, 'gi');
    const displayValue = league.leagueFullNameEN.replace(reg, "<span class='text-matched'>$1</span>");

    return (
        <div className="soccer-match-grid-item">
            <div className="soccer-match-grid-item-header">
                <div className="soccer-match-grid-item-league">
                    <i className="icon-livescore icon-live-start start-active" />
                    <div className="livescore-logo-team"><img loading="lazy" src={league.icon} /></div>
                    <span className="livescore-name-team" dangerouslySetInnerHTML={{ __html: displayValue }} />
                </div>
                <span className="icon-livescore icon-live-right" />
            </div>
            <div className="soccer-match-grid-item-body">
                {
                    league.matches.map(match => <Match key={match.matchId} searchKey={searchKey} match={match} />)
                }
            </div>
        </div>
    );
};

export default League;