import React, { PureComponent, Fragment } from 'react';
import MatchStage from '@jsroot/components/MatchStage';
import { MatchStatus } from '@jsroot/common/constants';
import CommonFunctions from '@jsroot/common/common-functions';
import Site from '@jsroot/common/site';

export default class Match extends PureComponent {
    constructor(props) {
        super(props);

        const match = this.props.match;
        const matchIds = CommonFunctions.getFavouriteMatches();
        const matchIndex = matchIds.findIndex(item => item === match.matchId);
        this.state = {
            isFavourite: matchIndex >= 0
        };
    }

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

    handleClick = event => {
        const className = event.target.className;
        if(className.includes('icon-livescore')) {
            return;
        }

        const matchId = this.props.match.matchId;
        const url = Site.resolveClientUrl(`lich-thi-dau/bong-da/chi-tiet/${matchId}`);
        if (event.ctrlKey) {
            window.open(url, '_blank');
        } else {
            window.location = url;
        }
    };

    handleUpdateFavourite = () => {
        const matchId = this.props.match.matchId;
        const isFavourite = CommonFunctions.updateFavouriteMatch(matchId);
        this.setState({ isFavourite: isFavourite });
    }

    render() {
        const { match, searchKey } = this.props;
        const isLiveMatch = match.status === MatchStatus.FirstHalf
            || match.status === MatchStatus.SecondHalf
            || match.status === MatchStatus.Intermission
            || match.status === MatchStatus.ExtraTime
            || match.status === MatchStatus.PenaltyShootout;
        const isIntermission = match.status === MatchStatus.Intermission;
        const { isFavourite } = this.state;

        return (
            <div className="group-match-item">
                <div className={`soccer-match-item ${isLiveMatch ? 'live' : ''} ${isIntermission ? 'live-intermission' : ''}`}
                    onClick={this.handleClick}
                >
                    <i onClick={this.handleUpdateFavourite} className={`icon-livescore ${isFavourite ? "icon-live-start start-active" : "icon-favourite-outline"}`} />
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
            </div>
        );
    }
}