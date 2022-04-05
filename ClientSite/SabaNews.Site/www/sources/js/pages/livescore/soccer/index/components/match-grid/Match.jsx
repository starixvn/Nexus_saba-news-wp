import React, { PureComponent, Fragment } from 'react';
import LangHelper from '@jsroot/common/langHelper';
import DateTimeService from '@jsroot/common/dateTimeService';
import { MatchStatus } from '@jsroot/common/constants';
import Site from '@jsroot/common/site';

export default class Match extends PureComponent {
    getMatchStage = match => {
        switch (match.status) {
            case MatchStatus.NotStarted:
                const startTime = DateTimeService.convertFromUTC(match.startTime);
                return <span className="font-weight-bold">{DateTimeService.toShortTimeFormat(startTime)}</span>;
                break;

            case MatchStatus.FirstHalf:
            case MatchStatus.SecondHalf:
                return <span>72'</span> ;
                break;

            case MatchStatus.Intermission:
                return <span>{LangHelper.getResource('Intermission')}</span>;
                break;

            case MatchStatus.Delay:
                return <span>{LangHelper.getResource('Delay')}</span>;
                break;

            case MatchStatus.Postpone:
                return <span>{LangHelper.getResource('Postponed')}</span>;
                break;

            case MatchStatus.TBD:
                return <span>{LangHelper.getResource('ToBeDecided')}</span>;
                break;

            default:
                return <span>{LangHelper.getResource('Finish')}</span>;
                break;
        }
    };

    getTeamStatus = (match, isHomeTeam) => {
        const homeFieldName = isHomeTeam ? 'homeTeam' : 'awayTeam';
        const awayFieldName = !isHomeTeam ? 'awayTeam' : 'homeTeam';
        switch (status) {
            case MatchStatus.Finished:
                if (match[`${homeFieldName}PenaltyScore`]
                    && match[`${homeFieldName}PenaltyScore`] > match[`${awayFieldName}PenaltyScore`]) {
                    return <li>PEN</li>;
                } else if (match[`${homeFieldName}AggregateScore`]
                    && match[`${homeFieldName}AggregateScore`] > match[`${awayFieldName}AggregateScore`]) {
                    return <li><i className="icon-live-up" /></li>;
                } else {
                    return <Fragment />;
                }
                break;

            default:
                return <Fragment />;
                break;
        }
    };

    getTeamScore = (status, totalScore, halfScore) => {
        switch (status) {
            case MatchStatus.NotStarted:
            case MatchStatus.Delay:
            case MatchStatus.Postponed:
            case MatchStatus.Cancel:
            case MatchStatus.TBD:
                return <span>&nbsp; - &nbsp;</span>;
                break;

            default:
                return <div><span className="total-score">{totalScore}</span> <span className="half-score">({halfScore})</span></div>;
                break;
        }
    };

    render() {
        const { match, searchKey } = this.props;
        const reg = new RegExp(`(${searchKey})`, 'gi');
        const displayHomeTeamValue = match.homeTeam.teamFullNameEN.replace(reg, "<span class='text-matched'>$1</span>");
        const displayAwayTeamValue = match.awayTeam.teamFullNameEN.replace(reg, "<span class='text-matched'>$1</span>");

        return (
            <div className="soccer-match-item" onClick={() => window.location = Site.resolveClientUrl(`lich-thi-dau/bong-da/chi-tiet/${match.matchId}`)}>
                <i className="icon-livescore icon-live-start-outline" />
                <div className="soccer-match-stage">{this.getMatchStage(match)}</div>
                <div className="soccer-match-team">
                    <div className="group-team">
                        <div className="soccer-team home-team">
                            <img className="soccer-team-logo" loading="lazy" src={match.homeTeam.icon} />
                            <div className="soccer-team-name" dangerouslySetInnerHTML={{ __html: displayHomeTeamValue }} />
                            <ul className="soccer-team-status">
                                {this.getTeamStatus(match, true)}
                            </ul>
                            <div className="soccer-team-result">
                                {this.getTeamScore(match.status, match.homeTeamTotalScore, match.homeTeamHalfScore)}
                            </div>
                        </div>
                        <div className="soccer-team away-team">
                            <img className="soccer-team-logo" loading="lazy" src={match.awayTeam.icon} />
                            <div className="soccer-team-name" dangerouslySetInnerHTML={{ __html: displayAwayTeamValue }} />
                            <ul className="soccer-team-status">
                                {this.getTeamStatus(match, false)}
                            </ul>
                            <div className="soccer-team-result">
                                {this.getTeamScore(match.status, match.awayTeamTotalScore, match.awayTeamHalfScore)}
                            </div>
                        </div>
                    </div>
                </div>
                <div className="soccer-match-star-icon">
                    <i className="icon-livescore icon-live-right" />
                </div>
            </div>
        );
    }
}