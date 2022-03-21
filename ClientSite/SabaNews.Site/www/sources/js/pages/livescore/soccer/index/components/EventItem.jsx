import React, { Component } from 'react';
import Match from './Match';

export default class EventItem extends Component {
    render() {
        const league = this.props.league;
        return (
            <div className="events-grid-item">
                <div className="events-grid-item-header">
                    <div className="events-grid-item-league">
                        <img className="logo" loading="lazy"
                            src={'https://apimanager.ydssp.com/uploads/teamIcon/1540/9b6c2MOb6oQ.png'} />
                        <span>{league.leagueEn}</span>
                    </div>
                    <div className="events-grid-item-schedule">LTĐ</div>
                    <div className="events-grid-item-result">KQ</div>
                    <div className="events-grid-item-standings">BXH</div>
                    <div className="events-grid-item-star-icon"><span className="fa fa-star-o" /></div>
                </div>
                <div className="events-grid-item-body">
                    {
                        league.matches.map(match => <Match key={match.matchId} match={match} />)
                    }
                </div>
            </div>
        );
    }
}