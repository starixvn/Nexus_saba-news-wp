import React, { PureComponent, Fragment } from 'react';
import MatchStage from '@jsroot/components/MatchStage';
import TeamScore from '../../../index/components/match-grid/TeamScore';
import TeamIcon from '@jsroot/components/Icon/TeamIcon';
import { MatchStatus } from '@jsroot/common/constants';
import CommonFunctions from '@jsroot/common/common-functions';
import Site from '@jsroot/common/site';

export default class Match extends PureComponent {
    constructor(props) {
        super(props);
    }

    getTeamStatus = (match, isHomeTeam) => {
        const homeFieldName = isHomeTeam ? 'homeTeam' : 'awayTeam';
        const awayFieldName = isHomeTeam ? 'awayTeam' : 'homeTeam';

        if (match.status === MatchStatus.Finished) {
            if (match[`${homeFieldName}PenaltyScore`]
                && match[`${homeFieldName}PenaltyScore`] > match[`${awayFieldName}PenaltyScore`]) {
                return <li>PEN</li>;
            } else if (match.isSecondLeg && match[`${homeFieldName}AggregateScore`]
                && match[`${homeFieldName}AggregateScore`] > match[`${awayFieldName}AggregateScore`]) {
                return <li>AGG</li>;
            } else {
                return <Fragment />;
            }
        }

        return <Fragment />;
    };

    onClick = event => {
        const className = event.target.className;
        if(className.includes('icon-livescore')) {
            return;
        }

        const { matchId, league, homeTeam, awayTeam } = this.props.match;
        const leagueName = Site.toSlug(CommonFunctions.getLeagueName(league));
        const homeTeamName = Site.toSlug(CommonFunctions.getTeamName(homeTeam));
        const awayTeamName = Site.toSlug(CommonFunctions.getTeamName(awayTeam));
        const url = Site.resolveClientUrl(`lich-thi-dau/bong-da/${leagueName}/${homeTeamName}-vs-${awayTeamName}/${matchId}`);
        if (event.ctrlKey) {
            window.open(url, '_blank');
        } else {
            window.location = url;
        }
    };

    render() {
        const { match } = this.props;
        const isLiveMatch = CommonFunctions.isLiveMatch(match.status);
        const isIntermission = match.status === MatchStatus.Intermission;
        return (
            <>
                <div className="group-match-item" onClick={this.onClick}>
                    <div className={`soccer-match-item ${isLiveMatch ? 'live' : ''} ${isIntermission ? 'live-intermission' : ''}`}
                    >
                        <MatchStage match={match} />
                        <div className="soccer-match-team">
                            <div className="group-team">
                                <div className="soccer-team home-team">
                                    <TeamIcon className="soccer-team-logo" src={match.homeTeam.icon} />
                                    <div className="soccer-team-name">{CommonFunctions.getTeamName(match.homeTeam)}</div>
                                    <ul className="soccer-team-status">
                                        {this.getTeamStatus(match, true)}
                                    </ul>
                                    <TeamScore match={match} isHomeTeam />
                                </div>
                                <div className="soccer-team away-team">
                                    <TeamIcon className="soccer-team-logo" src={match.awayTeam.icon} />
                                    <div className="soccer-team-name">{CommonFunctions.getTeamName(match.awayTeam)}</div>
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
            </>
        );
    }
}