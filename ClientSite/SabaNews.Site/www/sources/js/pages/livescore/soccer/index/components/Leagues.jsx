import React, { PureComponent } from 'react';
import LangHelper from '@jsroot/common/langHelper';

export default class Leagues extends PureComponent {
    render() {
        const leagues = [
            { leagueId: 1, name: 'Champions League' },
            { leagueId: 2, name: 'Europa League' },
            { leagueId: 3, name: 'Premier League' },
            { leagueId: 4, name: 'La Liga' },
            { leagueId: 5, name: 'La Liga' },
            { leagueId: 6, name: 'Serie A' },
            { leagueId: 7, name: 'Bundesliga' },
            { leagueId: 8, name: 'Ligue 1' },
            { leagueId: 9, name: 'V-League' },
            { leagueId: 10, name: 'Vòng Loại World Cup' }
        ];
        return (
            <div className="leagues">
                <div className="leagues-title"><span>{LangHelper.getResource('HotLeague')}</span></div>
                <div className="leagues-main">
                    {
                        leagues.map(league => {
                            return (
                                <div key={league.leagueId} className="leagues-item" title={league.name}>
                                    <div className="leagues-name">
                                        <img className="logo" loading="lazy"
                                            src={'https://apimanager.ydssp.com/uploads/teamIcon/1540/9b6c2MOb6oQ.png'} />
                                        <span className="text">{league.name}</span>
                                    </div>
                                    <div className="toggle-league"><span className="fa fa-star" /></div>
                                </div>
                            );
                        })
                    }
                </div>
            </div>
        );
    }
}