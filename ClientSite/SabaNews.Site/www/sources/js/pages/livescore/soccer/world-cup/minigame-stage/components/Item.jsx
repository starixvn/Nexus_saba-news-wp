import React, { PureComponent, Fragment } from 'react';
import CommonFunctions from '@jsroot/common/common-functions';
import TeamIcon from '@jsroot/components/Icon/TeamIcon';
import { MatchStatus, WorldCup, MatchResult } from '@jsroot/common/constants';
import TeamScore from '../../../index/components/match-grid/TeamScore';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import DateTimeService from '@jsroot/common/dateTimeService';
import moment from 'moment';

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
        const { match, votes } = this.props;
        let vote = null;
        var matchVote = votes.find(item => item.matchId === match.matchId && item.stage == window.stage);
        if(!matchVote) {
            return vote;
        }
        if(matchVote.teamId === 0) {
            vote = 0;
        } else if(match.homeTeam.teamId ===  matchVote.teamId) {
            vote = 1;
        } else if(match.awayTeam.teamId ===  matchVote.teamId) {
            vote = 2;
        }
        return vote;
    };

    handleVote = teamId => {
        const { match, votes } = this.props;
        var matchVote = votes.find(item => item.matchId === match.matchId && item.stage == window.stage);
        var startTime = DateTimeService.convertFromUTC(match.startTime);
        const currentTime = moment(DateTimeService.getCurrentClientDateTime());
        let minutes = startTime.diff(currentTime, 'minutes');
        if(match.status === MatchStatus.Finished
            || minutes <= 60
            || (matchVote && matchVote.teamId === teamId)) {
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
    }

    render() {
        const { match, votes } = this.props;
        const vote = this.checkVoteOption();
        return (
            <div>
                <div className="group-match-item" onClick={this.onClick}>
                    <div className={`soccer-match-item`}
                    >
                        <div className="soccer-match-date-result">
                        </div>
                        <div className="soccer-match-team">
                            <div className="group-team">
                                <div className="soccer-team home-team">
                                    <ul className="soccer-team-status">
                                        {this.getTeamStatus(match, true)}
                                    </ul>
                                    <div className={`soccer-team-name `} onClick={() => this.handleVote(match.homeTeam.teamId)}>{vote === 1 ? "(v)" : ''} {CommonFunctions.getTeamName(match.homeTeam)}</div>
                                    <TeamIcon className="soccer-team-logo" src={CommonFunctions.getTeamIcon(match.homeTeam)} />
                                </div>

                                <ul className="wc-round-score">
                                    <li className="total-score" onClick={() => this.handleVote(0)} > Hòa {vote === 0 ? "(v)" : ''}</li>
                                </ul>

                                <div className="soccer-team away-team">
                                    <TeamIcon className="soccer-team-logo" src={CommonFunctions.getTeamIcon(match.awayTeam)} />
                                    <div className={`soccer-team-name `}  onClick={() => this.handleVote(match.awayTeam.teamId)}>{CommonFunctions.getTeamName(match.awayTeam)} {vote === 2 ? "(v)" : ''}</div>
                                    <ul className="soccer-team-status">
                                        {this.getTeamStatus(match, false)}
                                    </ul>
                                    <TeamScore match={match} />
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
    const { votes } = state;
    return { votes };
};

export default connect(mapStateToProps, mapDispatchToProps)(Item);