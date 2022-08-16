import React, { PureComponent, Fragment } from 'react';
import MatchStage from '@jsroot/components/MatchStage';
import TeamScore from '../../../index/components/match-grid/TeamScore';
import TeamIcon from '@jsroot/components/Icon/TeamIcon';
import { MatchStatus } from '@jsroot/common/constants';
import CommonFunctions from '@jsroot/common/common-functions';
import DateTimeService from '@jsroot/common/dateTimeService';

export default class Item extends PureComponent {
    constructor(props) {
        super(props);
    }

    onClick = event => {
        const url = CommonFunctions.getMatchDetailPageUrl(this.props.match);
        if (event.ctrlKey) {
            window.open(url, '_blank');
        } else {
            window.location = url;
        }
    };

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

    render() {
        const { match } = this.props;
        const isLiveMatch = CommonFunctions.isLiveMatch(match.status);
        const isIntermission = match.status === MatchStatus.Intermission;
        const startTime = DateTimeService.convertFromUTC(match.startTime);
        return (
            <>
                <div className="group-match-item" onClick={this.onClick}>
                    <div className={`soccer-match-item ${isLiveMatch ? 'live' : ''} ${isIntermission ? 'live-intermission' : ''}`}
                    >
                        <div className="soccer-match-date-result">
                            <div className="group-live-icon">
                                <i className="icon-livescore icon-wc-live" />
                                <MatchStage match={match} />
                            </div>
                            <span className="wc-date">{startTime.format('DD/MM')}</span>
                        </div>
                        <div className="soccer-match-team">
                            <div className="group-team">
                                <div className="soccer-team home-team">
                                    <TeamIcon className="soccer-team-logo" src={match.homeTeam.icon} />
                                    <div className="soccer-team-name">{CommonFunctions.getTeamNameVN(match.homeTeam)}</div>
                                    <ul className="soccer-team-status">
                                        {this.getTeamStatus(match, true)}
                                    </ul>
                                    <TeamScore match={match} isHomeTeam />
                                </div>
                                <div className="soccer-team away-team">
                                    <TeamIcon className="soccer-team-logo" src={match.awayTeam.icon} />
                                    <div className="soccer-team-name">{CommonFunctions.getTeamNameVN(match.awayTeam)}</div>
                                    <ul className="soccer-team-status">
                                        {this.getTeamStatus(match, false)}
                                    </ul>
                                    <TeamScore match={match} />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </>
        );
    }
}