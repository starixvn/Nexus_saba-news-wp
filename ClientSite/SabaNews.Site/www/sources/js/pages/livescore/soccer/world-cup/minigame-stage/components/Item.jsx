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

    getVoteTeam = vote => {
        const { match } = this.props;
        if(vote === 1) {
            return (
                <span>Bạn dự đoán <b>{CommonFunctions.getTeamName(match.homeTeam)}</b> sẽ giành chiến thắng!</span>
            )
        } else if(vote === 2) {
            return (
                <span>Bạn dự đoán <b>{CommonFunctions.getTeamName(match.awayTeam)}</b> sẽ giành chiến thắng!</span>
            )
        } else if(vote === 0) {
            return (
                <span>Bạn dự đoán {CommonFunctions.getTeamName(match.homeTeam)} và {CommonFunctions.getTeamName(match.awayTeam)} sẽ <b>Hòa</b> nhau!</span>
            )
        }
        return <span></span>
    }

    render() {
        const { match, number } = this.props;
        const vote = this.checkVoteOption();
        return (
            <div className={`statistic-grid-item ${vote != null ? "active" : ""}`} onClick={this.onClick}>
                <div className="statistic-grid-item-header">
                    <div className="statistic-grid-item-round">
                        { `Trận 0${number + 1}: ${CommonFunctions.getTeamName(match.homeTeam)} vs ${CommonFunctions.getTeamName(match.awayTeam)}` }
                    </div>
                    <div className="statistic-grid-item-fav show-pc">{this.getVoteTeam(vote)}</div>
                </div>
                <div className="statistic-grid-item-body">
                    <div className="team-main">
                        <div className={`statistic-team statistic-team-home ${vote === 1 ? "active" : ""}`} onClick={() => this.handleVote(match.homeTeam.teamId)}>
                            <TeamIcon className="statistic-team-logo" src={CommonFunctions.getTeamIcon(match.homeTeam)} />
                            <div className="statistic-team-name">{CommonFunctions.getTeamName(match.homeTeam)}</div>
                    </div>
                        <div className={`statistic-team statistic-draw ${vote === 0 ? "active" : ""}`} onClick={() => this.handleVote(0)}>
                            <i className="icon-minigame_draw" />
                            <div className="statistic-team-name">Hòa</div>
                        </div>
                        <div className={`statistic-team statistic-team-away ${vote === 2 ? "active" : ""}`} onClick={() => this.handleVote(match.awayTeam.teamId)}>
                            <TeamIcon className="statistic-team-logo" src={CommonFunctions.getTeamIcon(match.awayTeam)} />
                            <div className="statistic-team-name">{CommonFunctions.getTeamName(match.awayTeam)}</div>
                    </div>
                    </div>
                    <div className="statistic-grid-item-fav show-mb">Bạn dự đoán <b>Qatar</b> sẽ giành chiến thắng!</div>
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