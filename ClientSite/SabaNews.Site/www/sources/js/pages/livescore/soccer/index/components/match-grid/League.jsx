import React from 'react';
import Match from './Match';
import TeamIcon from '@jsroot/components/Icon/TeamIcon';
import Site from '@jsroot/common/site';
import CommonFunctions from '@jsroot/common/common-functions';

const League = ({ league, selectedDate, searchKey, onUpdateFavouriteMatch }) => {
    const leagueName = CommonFunctions.getLeagueNameWithStage(league);
    const reg = new RegExp(`(${searchKey})`, 'gi');
    const displayValue = searchKey.length > 0 ? leagueName.replace(reg, "<span class='text-matched'>$1</span>") : leagueName;

    const handleClick = event => {
        const seasonId = league.season ? league.season.seasonId : 0;
        const url = Site.resolveClientUrl(`lich-thi-dau/bong-da/chi-tiet-giai-dau/${selectedDate}/${league.leagueId}/${seasonId}`);
        if (event.ctrlKey) {
            window.open(url, '_blank');
        } else {
            window.location = url;
        }
    };

    return (
        <div className="soccer-match-grid-item">
            <div className="soccer-match-grid-item-header" onClick={handleClick}>
                <div className="soccer-match-grid-item-league">
                    <i className="icon-livescore icon-live-start start-active" />
                    <div className="livescore-logo-team"><TeamIcon src={league.icon} /></div>
                    <span className="livescore-name-team" dangerouslySetInnerHTML={{ __html: displayValue }} />
                </div>
                <span className="icon-livescore icon-live-right" />
            </div>
            <div className="soccer-match-grid-item-body">
                {
                    league.matches.map(match =>
                        <Match key={match.matchId} searchKey={searchKey} match={match} onUpdateFavouriteMatch={onUpdateFavouriteMatch} />
                    )
                }
            </div>
        </div>
    );
};

export default League;