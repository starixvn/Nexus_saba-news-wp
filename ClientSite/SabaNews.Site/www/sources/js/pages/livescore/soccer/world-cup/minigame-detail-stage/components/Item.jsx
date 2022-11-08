import React, { PureComponent } from 'react';
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
        this.stage = parseInt(window.stage);
    }


    checkVoteOption = () => {
        const { match, vote } = this.props;
        let voteStatus = null;
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
        const result = this.stage !== 4 || (this.stage === 4
            && teams.includes(match.homeTeam.teamId)
            && teams.includes(match.awayTeam.teamId));
        return !result;
    };

    handleVote = async teamId => {
        const { match, vote } = this.props;
        if((this.stage === 4 && teamId === 0)
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
            const params = {
                stage: window.stage,
                teamId: teamId,
                matchId: match.matchId
            };
            this.props.updateVote(params).then(() => {
                this.props.getVotes();
            });
        });
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
            return <li>empty</li>;
        }
        if(result.status) {
            return <li>win</li>;
        }
        return <li>lose</li>;
    };

    getVoteScore = result => {
        if(result === null) {
            return <></>;
        }
        if(result.status) {
            return <li>{`+${result.score}`}</li>;
        }
        return <li>0</li>;
    };

    render() {
        const { match, result } = this.props;
        const voteStatus = this.checkVoteOption();
        return (
            <div>
                <div className="group-match-item" onClick={this.onClick}>
                    <div className={`soccer-match-item`}
                    >
                        <div className="soccer-match-team">
                            <div className="group-team">
                                <div className="soccer-team home-team">
                                    <div className={`soccer-team-name `} onClick={() => this.handleVote(match.homeTeam.teamId)}>
                                        {voteStatus === 1 ? '(v)' : ''} {CommonFunctions.getTeamName(match.homeTeam)}
                                    </div>
                                    <TeamIcon className="soccer-team-logo" src={CommonFunctions.getTeamIcon(match.homeTeam)} />
                                </div>

                                <ul className="wc-round-score">
                                    <li className="total-score" onClick={() => this.handleVote(0)} > Hòa {voteStatus === 0 ? '(v)' : ''}</li>
                                </ul>

                                <div className="soccer-team away-team">
                                    <TeamIcon className="soccer-team-logo" src={CommonFunctions.getTeamIcon(match.awayTeam)} />
                                    <div className={`soccer-team-name `} onClick={() => this.handleVote(match.awayTeam.teamId)}>
                                        {CommonFunctions.getTeamName(match.awayTeam)} {voteStatus === 2 ? '(v)' : ''}
                                    </div>
                                    <ul className="soccer-team-status">
                                        <li>{this.getTeamVote()}</li>
                                        {this.getVoteResult(result)}
                                        {this.getVoteScore(result)}
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