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

    renderTeamName = name => {
        const isShowMore = name.length > 11;
        const teamName = isShowMore ? name.substring(0, 11) + '....' : name;
        return (
            <li className="team-name" title={name}>{teamName}</li>
        )
    };

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

    getHalfScore = () => {
        const { match } = this.props;
        return (
            <>
                {
                    match.status === MatchStatus.Finished &&
                    <span>Hiệp 1: {match.homeTeamHalfScore} - {match.awayTeamHalfScore}</span>
                }
            </>
        );
    };

    getFinalScore = () => {
        const { match } = this.props;
        return (
            <>
                <span>{match.homeTeamExtraScore ? match.homeTeamExtraScore : match.homeTeamTotalScore}</span>
                <span className="detail-score-divider"> - </span>
                <span>{match.awayTeamExtraScore ? match.awayTeamExtraScore : match.awayTeamTotalScore}</span>
            </>
        )
    }


    render() {
        const { match } = this.props;
        const teamResult = CommonFunctions.getMatchResult(match.homeTeam.teamId, match);
        const isLive = CommonFunctions.isLiveMatch(match.status);
        const isIntermission = match.status === MatchStatus.Intermission;
        return (
            <>
                <div className={`duel-participant ${isLive ? 'live' : ''} ${isIntermission ? 'live-intermission' : ''}`}>
                    <div className="flex-league">
                        <div className="column column-left home-team">
                            <ul className="team">
                                {this.renderTeamName(CommonFunctions.getTeamName(match.homeTeam))}
                                <li className="team-logo">
                                    <TeamIcon className="" src={CommonFunctions.getTeamIcon(match.homeTeam)} />
                                </li>
                            </ul>
                        </div>
                        <div className="column column-center result-league">
                            <div class={`detail-score-wrapper ${isLive ? 'live' : ''}`}>
                                {match.status === MatchStatus.Finished && this.getFinalScore()}
                                {match.status !== MatchStatus.Finished && <MatchStage match={match} />}
                            </div>
                        </div>
                        <div className="column column-right away-team">
                            <ul className="team">
                                <li className="team-logo">
                                    <TeamIcon className="" src={CommonFunctions.getTeamIcon(match.awayTeam)} />
                                </li>
                                {this.renderTeamName(CommonFunctions.getTeamName(match.awayTeam))}
                            </ul>
                        </div>
                    </div>
                    <div className="flex-league">
                        <div className="column column-left home-team"></div>
                        <div className="column column-center">
                            <div className="soccer-match-stage">
                                {this.getHalfScore()}
                            </div>
                        </div>
                        <div className="column column-right away-team"></div>
                    </div>
                </div>
            </>
        );
    }
}