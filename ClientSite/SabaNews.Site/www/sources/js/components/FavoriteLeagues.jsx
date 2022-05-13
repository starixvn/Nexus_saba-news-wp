import React from 'react';
import TeamIcon from '@jsroot/components/Icon/TeamIcon';
import LangHelper from '@jsroot/common/langHelper';
import Site from '@jsroot/common/site';
import DateTimeService from '@jsroot/common/dateTimeService';
import CommonFunctions from '@jsroot/common/common-functions';

const FavoriteLeagues = ({ leagues }) => {
    const handleClick = (event, league) => {
        const leagueName = CommonFunctions.getLeagueName(league).toLowerCase().replace(' ', '-');
        const today = DateTimeService.formatDateTime(DateTimeService.getCurrentClientDateTime(), 'YYYY-MM-DD');
        const seasonId = league.currentSeasonId ? league.currentSeasonId : 0;
        const url = Site.resolveClientUrl(`lich-thi-dau/bong-da/${leagueName}/${seasonId}/${today}/${league.leagueId}`);
        if (event.ctrlKey) {
            window.open(url, '_blank');
        } else {
            window.location = url;
        }
    };

    return (
        <div className="leagues">
            <div className="leagues-title">
                <i className="icon-livescore icon-live-start" /> <span>{LangHelper.getResource('HotLeague')}</span>
            </div>
            <div className="leagues-main">
                {
                    leagues.map(league => {
                        return (
                            <div key={league.leagueId} className="leagues-item" onClick={event => handleClick(event, league)}>
                                <div className="leagues-name">
                                    <TeamIcon className="logo" src={league.icon} />
                                    <span className="text">{CommonFunctions.getLeagueName(league)}</span>
                                </div>
                                <div className="toggle-league">
                                    <span className="icon-livescore icon-live-start start-active" />
                                </div>
                            </div>
                        );
                    })
                }
            </div>
        </div>
    );
};

export default FavoriteLeagues;