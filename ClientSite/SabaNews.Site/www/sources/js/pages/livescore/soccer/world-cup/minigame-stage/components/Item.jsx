import React, { PureComponent } from 'react';
import CommonFunctions from '@jsroot/common/common-functions';
import TeamIcon from '@jsroot/components/Icon/TeamIcon';
import { MatchStatus } from '@jsroot/common/constants';
import TeamScore from '../../../index/components/match-grid/TeamScore';
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

    checkKnockoutMatch = () => {
        const { match, teams } = this.props;
        var result = window.stage != 4 || (window.stage == 4
            && teams.includes(match.homeTeam.teamId)
            && teams.includes(match.awayTeam.teamId));
        return !result;
    };

    handleVote = async teamId => {
        const { match, votes } = this.props;
        var vote = votes.find(item => item.matchId === match.matchId && item.stage == window.stage);
        if((window.stage == 4 && teamId === 0)
        || (vote && vote.teamId === teamId)) {
            return;
        }
        const url = Site.resolveClientUrl('worldcup2022/GetMinigameVoteMatch');
        await DataService.postDataAsync(url, {matchId: match.matchId}).then(response => {
            const voteMatch = response.data;
            const startTime = DateTimeService.convertFromUTC(voteMatch.startTime);
            const currentTime = moment(DateTimeService.getCurrentClientDateTime());
            const minutes = startTime.diff(currentTime, 'minutes');
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
    }

    render() {
        const { match, number } = this.props;
        const vote = this.checkVoteOption();
        return (
            <div>
                <div className="group-match-item" onClick={this.onClick}>
                    <div className={`soccer-match-item`}
                    >
                        <div className="soccer-match-date-result">
                            { `Trận 0${number + 1}: ${CommonFunctions.getTeamName(match.homeTeam)} vs ${CommonFunctions.getTeamName(match.awayTeam)}` }
                        </div>
                        <div className="soccer-match-team">
                            <div className="group-team">
                                <div className="soccer-team home-team">
                                    <div className={`soccer-team-name `} onClick={() => this.handleVote(match.homeTeam.teamId)}>{vote === 1 ? "(v)" : ''} {CommonFunctions.getTeamName(match.homeTeam)}</div>
                                    <TeamIcon className="soccer-team-logo" src={CommonFunctions.getTeamIcon(match.homeTeam)} />
                                </div>

                                <ul className="wc-round-score">
                                    <li className="total-score" onClick={() => this.handleVote(0)} > Hòa {vote === 0 ? "(v)" : ''}</li>
                                </ul>

                                <div className="soccer-team away-team">
                                    <TeamIcon className="soccer-team-logo" src={CommonFunctions.getTeamIcon(match.awayTeam)} />
                                    <div className={`soccer-team-name `}  onClick={() => this.handleVote(match.awayTeam.teamId)}>{CommonFunctions.getTeamName(match.awayTeam)} {vote === 2 ? "(v)" : ''}</div>
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
    const { votes, teams } = state;
    return { votes, teams };
};

export default connect(mapStateToProps, mapDispatchToProps)(Item);