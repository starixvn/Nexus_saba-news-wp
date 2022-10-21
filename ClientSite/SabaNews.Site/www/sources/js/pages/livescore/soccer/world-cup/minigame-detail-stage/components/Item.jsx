import React, { PureComponent, Fragment } from 'react';
import CommonFunctions from '@jsroot/common/common-functions';
import TeamIcon from '@jsroot/components/Icon/TeamIcon';
import { MatchStatus } from '@jsroot/common/constants';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import DateTimeService from '@jsroot/common/dateTimeService';
import moment from 'moment';
import Site from '@jsroot/common/site';
import DataService from '@jsroot/common/dataService';

export class Item extends PureComponent {
    constructor(props) {
        super(props);
    }

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

    checkVoteOption = () => {
        const { match, vote } = this.props;
        let voteStatus = null;
        if(!vote) {
            return voteStatus;
        }
        if(vote.teamId === 0) {
            voteStatus = 0;
        } else if(match.homeTeam.teamId ===  vote.teamId) {
            voteStatus = 1;
        } else if(match.awayTeam.teamId ===  vote.teamId) {
            voteStatus = 2;
        }
        return voteStatus;
    };

    checkKnockoutMatch = () => {
        const { match, teams } = this.props;
        var result = window.stage != 4 || (window.stage == 4
            && teams.includes(match.homeTeam.teamId)
            && teams.includes(match.awayTeam.teamId));
        return !result;
    };

    handleVote = async teamId => {
        const { match, vote } = this.props;
        if(vote && vote.teamId === teamId) {
            return;
        }
        const url = Site.resolveClientUrl('worldcup2022/GetMinigameVoteMatch');
        await DataService.postDataAsync(url, {matchId: match.matchId}).then(response => {
            const voteMatch = response.data;
            var startTime = DateTimeService.convertFromUTC(voteMatch.startTime);
            const currentTime = moment(DateTimeService.getCurrentClientDateTime());
            let minutes = startTime.diff(currentTime, 'minutes');
            if(voteMatch.status === MatchStatus.Finished
                || minutes <= 30
                || this.checkKnockoutMatch()) {
                return;
            }
            var params = {
                stage: window.stage,
                teamId: teamId,
                matchId: match.matchId
            };
            this.props.updateVote(params).then(() => {
                this.props.getVotes();
            });
        })
    };

    getTeamVote = () => {
        const { match, vote } = this.props;
        let voteTeamName = '';
        if(match.homeTeam.teamId === vote.teamId) {
            voteTeamName = CommonFunctions.getTeamName(match.homeTeam);
        } else if(match.awayTeam.teamId === vote.teamId) {
            voteTeamName = CommonFunctions.getTeamName(match.awayTeam);
        } else if(vote.teamId === 0) {
            voteTeamName = 'Hòa';
        }
        return voteTeamName;
    };

    getVoteResult = result => {
        if(result === null) {
            return 'empty';
        }
        if(result.status) {
            return 'win';
        }
        return 'lose';
    };

    getVoteScore = result => {
        if(result === null) {
            return '';
        }
        if(result.status) {
            return `+${result.score}`;
        }
        return 0;
    };

    render() {
        const { match, vote, result } = this.props;
        const voteStatus = this.checkVoteOption();
        return (
            <div>
                <div className="group-match-item" onClick={this.onClick}>
                    <div className={`soccer-match-item`}
                    >
                        <div className="soccer-match-team">
                            <div className="group-team">
                                <div className="soccer-team home-team">
                                    <ul className="soccer-team-status">
                                        {this.getTeamStatus(match, true)}
                                    </ul>
                                    <div className={`soccer-team-name `} onClick={() => this.handleVote(match.homeTeam.teamId)}>
                                        {voteStatus === 1 ? "(v)" : ''} {CommonFunctions.getTeamName(match.homeTeam)}
                                    </div>
                                    <TeamIcon className="soccer-team-logo" src={CommonFunctions.getTeamIcon(match.homeTeam)} />
                                </div>

                                <ul className="wc-round-score">
                                    <li className="total-score" onClick={() => this.handleVote(0)} > Hòa {voteStatus === 0 ? "(v)" : ''}</li>
                                </ul>

                                <div className="soccer-team away-team">
                                    <TeamIcon className="soccer-team-logo" src={CommonFunctions.getTeamIcon(match.awayTeam)} />
                                    <div className={`soccer-team-name `} onClick={() => this.handleVote(match.awayTeam.teamId)}>
                                        {CommonFunctions.getTeamName(match.awayTeam)} {voteStatus === 2 ? "(v)" : ''}
                                    </div>
                                    <ul className="soccer-team-status">
                                        <li>{this.getTeamVote()}</li>
                                        <li>{this.getVoteResult(result)}</li>
                                        <li>{this.getVoteScore(result)}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { votes, teams } = state;
    return { votes, teams };
};

export default connect(mapStateToProps, mapDispatchToProps)(Item);