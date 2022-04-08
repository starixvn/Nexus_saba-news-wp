import React, { PureComponent } from 'react';
import LangHelper from '@jsroot/common/langHelper';

export default class Leagues extends PureComponent {
    render() {
        return (
            <div className="leagues">
                <div className="leagues-title"> <i className="icon-livescore icon-live-start" /> <span>{LangHelper.getResource('HotLeague')}</span></div>
                <div className="leagues-main">
                    {
                        this.props.leagues.map(league => {
                            return (
                                <div key={league.leagueId} className="leagues-item">
                                    <div className="leagues-name">
                                        <img className="logo" loading="lazy" src={league.icon} />
                                        <span className="text">{league.leagueFullNameEN}</span>
                                    </div>
                                    <div className="toggle-league"><span className="icon-livescore icon-live-start start-active" /></div>
                                </div>
                            );
                        })
                    }
                </div>
            </div>
        );
    }
}