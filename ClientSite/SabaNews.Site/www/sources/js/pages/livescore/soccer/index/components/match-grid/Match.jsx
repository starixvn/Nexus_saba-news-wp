import React, { PureComponent, Fragment } from 'react';
import MatchStage from './MatchStage';
import { MatchStatus } from '@jsroot/common/constants';
import Site from '@jsroot/common/site';
import CommonFunctions from '@jsroot/common/common-functions';

export default class Match extends PureComponent {
    getTeamStatus = (match, isHomeTeam) => {
        const homeFieldName = isHomeTeam ? 'homeTeam' : 'awayTeam';
        const awayFieldName = isHomeTeam ? 'awayTeam' : 'homeTeam';

        switch (match.status) {
            case MatchStatus.Finished:
                if (match[`${homeFieldName}PenaltyScore`]
                    && match[`${homeFieldName}PenaltyScore`] > match[`${awayFieldName}PenaltyScore`]) {
                    return <li>PEN</li>;
                } else if (match[`${homeFieldName}AggregateScore`]
                    && match[`${homeFieldName}AggregateScore`] > match[`${awayFieldName}AggregateScore`]) {
                    return <li>AGG</li>;
                } else {
                    return <Fragment />;
                }
                break;

            default:
                return <Fragment />;
                break;
        }
    };

    getTeamScore = (match, isHomeTeam) => {
        const fieldName = isHomeTeam ? 'homeTeam' : 'awayTeam';
        switch (match.status) {
            case MatchStatus.NotStarted:
            case MatchStatus.Delay:
            case MatchStatus.Postponed:
            case MatchStatus.Cancel:
            case MatchStatus.TBD:
                return <span>&nbsp; - &nbsp;</span>;
                break;

            default:
                const totalScore = match[`${fieldName}ExtraScore`] ? match[`${fieldName}ExtraScore`] : match[`${fieldName}TotalScore`];
                const halfScore = match[`${fieldName}HalfScore`];
                return <div>
                    <span className="total-score">{totalScore}</span> <span className="half-score">({halfScore})</span></div>;
                break;
        }
    };

    getTeamName = (team, searchKey) => {
        const reg = new RegExp(`(${searchKey})`, 'gi');
        const teamName = CommonFunctions.getTeamName(team);
        return searchKey.length > 0 ? teamName.replace(reg, "<span class='text-matched'>$1</span>") : teamName;
    };

    render() {
        const { match, searchKey } = this.props;
        const isLiveMatch = match.status === MatchStatus.FirstHalf
            || match.status === MatchStatus.SecondHalf
            || match.status === MatchStatus.Intermission
            || match.status === MatchStatus.ExtraTime
            || match.status === MatchStatus.PenaltyShootout;
        const isIntermission = match.status === MatchStatus.Intermission;

        return (
            <div className={`soccer-match-item ${isLiveMatch ? 'live' : ''} ${isIntermission ? 'live-intermission' : ''}`}
                onClick={() => window.location = Site.resolveClientUrl(`lich-thi-dau/bong-da/chi-tiet/${match.matchId}`)}
            >
                <i className="icon-livescore icon-favourite-outline" />
                <MatchStage match={match} />
                <div className="soccer-match-team">
                    <div className="group-team">
                        <div className="soccer-team home-team">
                            <img className="soccer-team-logo" loading="lazy" src={match.homeTeam.icon} />
                            <div className="soccer-team-name" dangerouslySetInnerHTML={{ __html: this.getTeamName(match.homeTeam, searchKey) }} />
                            <ul className="soccer-team-status">
                                {this.getTeamStatus(match, true)}
                            </ul>
                            <div className="soccer-team-result">
                                {this.getTeamScore(match, true)}
                            </div>
                        </div>
                        <div className="soccer-team away-team">
                            <img className="soccer-team-logo" loading="lazy" src={match.awayTeam.icon} />
                            <div className="soccer-team-name" dangerouslySetInnerHTML={{ __html: this.getTeamName(match.awayTeam, searchKey) }} />
                            <ul className="soccer-team-status">
                                {this.getTeamStatus(match, false)}
                            </ul>
                            <div className="soccer-team-result">
                                {this.getTeamScore(match, false)}
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