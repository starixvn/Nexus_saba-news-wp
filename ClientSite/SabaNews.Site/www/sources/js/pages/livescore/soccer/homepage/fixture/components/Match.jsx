import React, { PureComponent, Fragment } from 'react';
import DateTimeService from '@jsroot/common/dateTimeService';
import MatchStage from '@jsroot/components/MatchStage';
import { MatchStatus, MatchResult } from '@jsroot/common/constants';
import TeamScore from '../../../index/components/match-grid/TeamScore';
import TeamIcon from '@jsroot/components/Icon/TeamIcon';
import CommonFunctions from '@jsroot/common/common-functions';


export default class Match extends PureComponent {
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

    renderTeamName = name =>  name.length > 11 ? name.substring(0, 11) + '....' : name;

    renderTeamStatus = (match, isHomeTeam) => {
        const hAttr = isHomeTeam ? 'homeTeam' : 'awayTeam';
        const aAttr = isHomeTeam ? 'awayTeam' : 'homeTeam';

        if (match.status === MatchStatus.Finished) {
            if (match[`${hAttr}PenaltyScore`]
                && match[`${hAttr}PenaltyScore`] > match[`${aAttr}PenaltyScore`]) {
                return <li>PEN</li>;
            } else if (match.isSecondLeg && match[`${hAttr}AggregateScore`]
                && match[`${hAttr}AggregateScore`] > match[`${aAttr}AggregateScore`]) {
                return <li>AGG</li>;
            } else {
                return <Fragment />;
            }
        }

        return <Fragment />;
    };


    render() {
        const { match } = this.props;
        const matchStartTime = DateTimeService.convertFromUTC(match.startTime);
        const teamResult = CommonFunctions.getMatchResult(match.homeTeam.teamId, match);
        const isLive = CommonFunctions.isLiveMatch(match.status);
        const isIntermission = match.status === MatchStatus.Intermission;
        return (
            <>
                <div className="duel-participant live live-intermission"> 
                    <div className="flex-league">
                        <div className="column column-left home-team">
                            <ul className="team">
                                <li className="team-name">Real Betis</li>
                                <li className="team-logo">
                                    <img loading="lazy" src="https://sports-cdn.zgsmxw.com/uploads/teamIcon/1533/9zvrnWACkbD.png" />
                                </li>
                            </ul>
                        </div>
                        <div className="column column-center result-league">
                            <div class="detail-score-wrapper live">
                                <span>1</span>
                                <span class="detail-score-divider"> - </span>
                                <span>2</span>
                            </div>
                        </div>
                        <div className="column column-right away-team">
                            <ul className="team">
                                <li className="team-logo">
                                    <img loading="lazy" src="https://sports-cdn.zgsmxw.com/uploads/teamIcon/1533/9zvrnWACkbD.png" />
                                </li>
                                <li className="team-name">Real Betis</li>
                            </ul>
                        </div>
                    </div>
                    <div className="flex-league">
                        <div className="column column-left home-team"></div>
                        <div className="column column-center">
                            <div className="soccer-match-stage">
                                <span>Hiệp 1: 1-0</span>
                            </div>
                        </div>
                        <div className="column column-right away-team"></div>
                    </div>
                </div>
                {/* <div className="group-match-item" onClick={this.onClick}>
                    <div className={`soccer-match-item ${isLive ? 'live' : ''} ${isIntermission ? 'live-intermission' : ''}`}
                    >
                        <div className="soccer-match-date-result">
                            <div className="group-live-icon">
                                <i className="icon-livescore icon-wc-live" />
                                <MatchStage match={match} />
                            </div>
                            <span className="wc-date">{matchStartTime.format('DD/MM')}</span>
                        </div>
                        <div className="soccer-match-team">
                            <div className="group-team">
                                <div className="soccer-team home-team">
                                    <TeamIcon className="soccer-team-logo" src={CommonFunctions.getTeamIcon(match.homeTeam)} />
                                    <div className={`soccer-team-name ${teamResult === MatchResult.Win ? 'win': ''}`}>
                                        {this.renderTeamName(CommonFunctions.getTeamName(match.homeTeam))}
                                    </div>
                                    <TeamScore match={match}  isHomeTeam   />
                                    <ul className="soccer-team-status">
                                        {this.renderTeamStatus(match, true)}
                                    </ul>
                                </div>
                                <div className="soccer-team away-team">
                                    <TeamIcon className="soccer-team-logo" src={CommonFunctions.getTeamIcon(match.awayTeam)} />
                                    <div className={`soccer-team-name ${teamResult === MatchResult.Lose ? 'win': ''}`}>
                                        {this.renderTeamName(CommonFunctions.getTeamName(match.awayTeam))}
                                    </div>
                                    <ul className="soccer-team-status">
                                        {this.renderTeamStatus(match, false)}
                                    </ul>
                                    <TeamScore match={match} />
                                </div>
                            </div>
                        </div>
                    </div>
                </div> */}
            </>
        );
    }
}