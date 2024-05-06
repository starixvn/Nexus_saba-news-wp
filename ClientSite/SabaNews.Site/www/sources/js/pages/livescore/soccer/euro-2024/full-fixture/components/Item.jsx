import React, { PureComponent, Fragment } from 'react';
import MatchStage from '@jsroot/components/MatchStage';
import TeamScore from '../../../index/components/match-grid/TeamScore';
import TeamIcon from '@jsroot/components/Icon/TeamIcon';
import { MatchStatus, WorldCup, MatchResult } from '@jsroot/common/constants';
import CommonFunctions from '@jsroot/common/common-functions';

export default class Item extends PureComponent {
    constructor(props) {
        super(props);
    }

    onClick = event => {
        const url = CommonFunctions.getMatchDetailPageUrl(this.props.match, WorldCup.MatchDetailUrl);
        if (event.ctrlKey) {
            window.open(url, '_blank');
        } else {
            window.location = url;
        }
    };

    getTeamStatus = (match, isHomeTeam) => {
        const homeField = isHomeTeam ? 'homeTeam' : 'awayTeam';
        const awayField = isHomeTeam ? 'awayTeam' : 'homeTeam';

        if (match.status === MatchStatus.Finished) {
            if (match[`${homeField}PenaltyScore`]
                && match[`${homeField}PenaltyScore`] > match[`${awayField}PenaltyScore`]) {
                return <li>PEN</li>;
            } else if (match.isSecondLeg && match[`${homeField}AggregateScore`]
                && match[`${homeField}AggregateScore`] > match[`${awayField}AggregateScore`]) {
                return <li>AGG</li>;
            } else {
                return <Fragment />;
            }
        }

        return <Fragment />;
    };

    getScores = match => {
        switch (match.status) {
            case MatchStatus.NotStarted:
            case MatchStatus.Delay:
            case MatchStatus.Postpone:
            case MatchStatus.Cancel:
            case MatchStatus.TBD:
                return (
                    <div className="euro-round-score">
                        <div className="total-score"> VS </div>
                    </div>
                );
            default:
                return (
                    <div className="euro-round-score">
                        <div className="total-score">
                            <div className="number">{match.homeTeamExtraScore ? match.homeTeamExtraScore : match.homeTeamTotalScore}</div>
                            <div className="number">{match.awayTeamExtraScore ? match.awayTeamExtraScore : match.awayTeamTotalScore}</div>
                        </div>
                        <div className="haft-score">
                            <span>  HT: </span>
                            <span>{match.homeTeamHalfScore}</span>
                            <span> - </span>
                            <span>{match.awayTeamHalfScore}</span>
                            <div className="end-round">Kết thúc</div>
                        </div>
                    </div>
                );
        }
    };

    render() {
        const { match } = this.props;
        const isLiveMatch = CommonFunctions.isLiveMatch(match.status);
        const isIntermission = match.status === MatchStatus.Intermission;
        const matchResult = CommonFunctions.getMatchResult(match.homeTeam.teamId, match);
        return (
            <>
                <div className="soccer-match-grid-items" onClick={this.onClick}>
                    <div className="group-match-item" onClick={this.onClick}>
                        <div className={`euro-match-item ${isLiveMatch ? 'live' : ''} ${isIntermission ? 'live-intermission' : ''}`}
                        >
                            <div className="euro-match-team">
                                <div className="euro-match-team-home">
                                    <TeamIcon className="soccer-team-logo" src={CommonFunctions.getTeamIcon(match.homeTeam)} />
                                    <div className={`soccer-team-name ${matchResult === MatchResult.Win ? 'win': ''}`}>{CommonFunctions.getTeamName(match.homeTeam)}</div>
                                </div>
                                <div className="euro-match-team-away">
                                    <TeamIcon className="soccer-team-logo" src={CommonFunctions.getTeamIcon(match.awayTeam)} />
                                    <div className={`soccer-team-name ${matchResult === MatchResult.Lose ? 'win': ''}`}>{CommonFunctions.getTeamName(match.awayTeam)}</div>
                                </div>
                            </div>
                            <div className="euro-result">
                                {this.getScores(match)}
                                {/* <div className="group-live-icon">
                                    <MatchStage match={match} />
                                </div> */}
                            </div>



                            {/* <div className="soccer-match-date-result">
                                <div className="group-live-icon">
                                    <MatchStage match={match} />
                                </div>
                            </div>
                            <div className="soccer-match-team">
                                <div className="group-team">
                                    <div className="soccer-team home-team">
                                        <ul className="soccer-team-status">
                                            {this.getTeamStatus(match, true)}
                                        </ul>
                                        <TeamScore match={match} isHomeTeam />
                                        <div className={`soccer-team-name ${matchResult === MatchResult.Win ? 'win': ''}`}>{CommonFunctions.getTeamName(match.homeTeam)}</div>
                                        <TeamIcon className="soccer-team-logo" src={CommonFunctions.getTeamIcon(match.homeTeam)} />
                                    </div>

                                    {this.getScores(match)}

                                    <div className="soccer-team away-team">
                                        <TeamIcon className="soccer-team-logo" src={CommonFunctions.getTeamIcon(match.awayTeam)} />
                                        <div className={`soccer-team-name ${matchResult === MatchResult.Lose ? 'win': ''}`}>{CommonFunctions.getTeamName(match.awayTeam)}</div>
                                        <ul className="soccer-team-status">
                                            {this.getTeamStatus(match, false)}
                                        </ul>
                                        <TeamScore match={match} />
                                    </div>
                                </div>
                            </div> */}
                        </div>
                    </div>
                </div>
            </>
        );
    }
}