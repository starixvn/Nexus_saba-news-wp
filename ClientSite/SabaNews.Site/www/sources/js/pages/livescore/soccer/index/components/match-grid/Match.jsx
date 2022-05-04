import React, { Component, Fragment } from 'react';
import MatchStage from '@jsroot/components/MatchStage';
import TeamScore from './TeamScore';
import TeamIcon from '@jsroot/components/Icon/TeamIcon';
import { MatchStatus } from '@jsroot/common/constants';
import CommonFunctions from '@jsroot/common/common-functions';
import Site from '@jsroot/common/site';
import { toast } from 'react-toastify';
import FavouriteIcon from '@jsroot/components/Icon/FavouriteIcon';
toast.configure();

export default class Match extends Component {
    getTeamStatus = (match, isHomeTeam) => {
        const homeFieldName = isHomeTeam ? 'homeTeam' : 'awayTeam';
        const awayFieldName = isHomeTeam ? 'awayTeam' : 'homeTeam';

        if (match.status === MatchStatus.Finished) {
            if (match[`${homeFieldName}PenaltyScore`]
                && match[`${homeFieldName}PenaltyScore`] > match[`${awayFieldName}PenaltyScore`]) {
                return <li>PEN</li>;
            } else {
                if (match[`${homeFieldName}AggregateScore`]
                    && match[`${homeFieldName}AggregateScore`] > match[`${awayFieldName}AggregateScore`]) {
                    return <li>AGG</li>;
                }
            }
        }

        return <Fragment />;
    };

    getTeamName = (team, searchKey) => {
        const reg = new RegExp(`(${searchKey})`, 'gi');
        const teamName = CommonFunctions.getTeamName(team);
        return searchKey.length > 0 ? teamName.replace(reg, "<span class='text-matched'>$1</span>") : teamName;
    };

    handleClick = event => {
        const className = event.target.className;
        console.log(className);
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

    onUpdateFavouriteMatch = (matchId) => {
        if(typeof(this.props.onUpdateFavouriteMatch) === 'function') {
            this.props.onUpdateFavouriteMatch(matchId);
        }
    }


    render() {
        const { match, searchKey } = this.props;
        const isLiveMatch = CommonFunctions.isLiveMatch(match.status);
        const isIntermission = match.status === MatchStatus.Intermission;

        return (
            <div className="group-match-item"  onClick={this.handleClick}>
                <div className={`soccer-match-item ${isLiveMatch ? 'live' : ''} ${isIntermission ? 'live-intermission' : ''}`}
                >
                    <FavouriteIcon matchId={match.matchId} onUpdateFavouriteMatch = {this.onUpdateFavouriteMatch} />
                    <MatchStage match={match} />
                    <div className="soccer-match-team">
                        <div className="group-team">
                            <div className="soccer-team home-team">
                                <TeamIcon className="soccer-team-logo" src={match.homeTeam.icon} />
                                <div className="soccer-team-name" dangerouslySetInnerHTML={{ __html: this.getTeamName(match.homeTeam, searchKey) }} />
                                <ul className="soccer-team-status">
                                    {this.getTeamStatus(match, true)}
                                </ul>
                                <TeamScore match={match} isHomeTeam />
                            </div>
                            <div className="soccer-team away-team">
                                <TeamIcon className="soccer-team-logo" src={match.awayTeam.icon} />
                                <div className="soccer-team-name" dangerouslySetInnerHTML={{ __html: this.getTeamName(match.awayTeam, searchKey) }} />
                                <ul className="soccer-team-status">
                                    {this.getTeamStatus(match, false)}
                                </ul>
                                <TeamScore match={match} />
                            </div>
                        </div>
                    </div>
                    <div className="soccer-match-star-icon">
                        <i className="icon-live-right" />
                    </div>
                </div>
            </div>
        );
    }
}