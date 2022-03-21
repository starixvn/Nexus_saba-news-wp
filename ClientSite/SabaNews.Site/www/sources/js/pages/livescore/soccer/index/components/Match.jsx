import React, { PureComponent } from 'react';

export default class Match extends PureComponent {

    getMatchStage = match => {
        switch (match.status) {
            case 0:
                return <span>{match.schedule}</span>;
                break;
            case 10:
            case 30:
                return <span>{match.matchTime}</span>;
                break;
            default:
                return <span>Kết thúc</span>;
                break;
        }
    };

    getMatchScores = match => {
        switch (match.status) {
            case 0:
                return <span>&nbsp; - &nbsp;</span>;
                break;
            default:
                return <>
                    <div className="font-weight-bold">
                        <span>{match.homeTeam.fullScore}</span>&nbsp; - &nbsp;<span>{match.awayTeam.fullScore}</span>
                    </div>
                    <div>
                        H1: <span>{match.homeTeam.halfScore}</span>&nbsp; - &nbsp;<span>{match.awayTeam.halfScore}</span>
                    </div>
                </>;
                break;
        }
    };

    render() {
        const match = this.props.match;
        return (
            <div className={`events-match border-bottom ${match.status === 10 || match.status === 30 ? 'live' : ''}`}>
                <div className="events-match-stage">{this.getMatchStage(match)}</div>
                <div className="events-match-participant-home">{match.homeTeam.nameEn}</div>
                <img className="events-match-participant-home-logo" loading="lazy"
                    src={match.homeTeam.icon} />
                <div className="events-match-participant-away">{match.awayTeam.nameEn}</div>
                <img className="events-match-participant-away-logo" loading="lazy"
                    src={match.awayTeam.icon} />
                <div className="events-match-scores">{this.getMatchScores(match)}</div>
                <div className="events-match-star-icon">
                    <span className="fa fa-star-o" />
                </div>
            </div>
        );
    }
}