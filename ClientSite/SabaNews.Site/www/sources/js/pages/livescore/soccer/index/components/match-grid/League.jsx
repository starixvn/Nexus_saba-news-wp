import React, { PureComponent } from 'react';
import Match from './Match';

export default class League extends PureComponent {
    render() {
        const { league, searchKey } = this.props;
        const reg = new RegExp(`(${searchKey})`, 'gi');
        const displayValue = league.leagueFullNameEN.replace(reg, "<span class='text-matched'>$1</span>");

        return (
            <div className="soccer-match-grid-item">
                <div className="soccer-match-grid-item-header">
                    <div className="soccer-match-grid-item-league">
                        <img className="logo" loading="lazy" src={league.icon} />
                        <span dangerouslySetInnerHTML={{ __html: displayValue }} />
                    </div>
                    <div className="soccer-match-grid-item-schedule">LTĐ</div>
                    <div className="soccer-match-grid-item-result">KQ</div>
                    <div className="soccer-match-grid-item-standings">BXH</div>
                    <div className="soccer-match-grid-item-star-icon"><span className="fa fa-star-o" /></div>
                </div>
                <div className="soccer-match-grid-item-body">
                    {
                        league.matches.map(match => <Match key={match.matchId} searchKey={searchKey} match={match} />)
                    }
                </div>
            </div>
        );
    }
}