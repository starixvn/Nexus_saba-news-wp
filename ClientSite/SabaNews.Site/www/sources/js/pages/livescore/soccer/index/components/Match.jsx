import React, { PureComponent } from 'react';
import LangHelper from '@jsroot/common/langHelper';
import { MatchStatus } from '@jsroot/common/constants';

export default class Match extends PureComponent {
    getMatchStage = match => {
        switch (match.status) {
            case MatchStatus.NotStarted:
                return <span>{match.schedule}</span>;
                break;

            case MatchStatus.FirstHalf:
            case MatchStatus.SecondHalf:
                return <span>{match.matchTime}</span>;
                break;

            default:
                return <span>{LangHelper.getResource('Finish')}</span>;
                break;
        }
    };

    getMatchScores = match => {
        switch (match.status) {
            case MatchStatus.NotStarted:
                return <span>&nbsp; - &nbsp;</span>;
                break;
            default:
                return <>
                    <div className="font-weight-bold">
                        <span>{match.homeTeamTotalScore}</span>&nbsp; - &nbsp;<span>{match.awayTeamTotalScore}</span>
                    </div>
                    <div>
                        H1: <span>{match.homeTeamHalfScore}</span>&nbsp; - &nbsp;<span>{match.awayTeamHalfScore}</span>
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