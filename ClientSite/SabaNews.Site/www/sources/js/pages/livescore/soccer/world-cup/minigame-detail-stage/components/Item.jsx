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
import { EqualHeight, EqualHeightElement } from 'react-equal-height';

export class Item extends PureComponent {
    constructor(props) {
        super(props);
        this.stage = parseInt(window.stage, 10);
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
        const urlVoteMatch = Site.resolveClientUrl('worldcup2022/GetMinigameVoteMatch');
        await DataService.postDataAsync(urlVoteMatch, {matchId: match.matchId}).then(response => {
            const voteMatch = response.data;
            const startTimeVote = DateTimeService.convertFromUTC(voteMatch.startTime);
            const currentTime = moment(DateTimeService.getCurrentClientDateTime());
            const minutes = startTimeVote.diff(currentTime, 'minutes');
            if(voteMatch.status === MatchStatus.Finished
                || minutes <= 30 || this.checkKnockoutMatch()) {
                return;
            }
            const params = {
                teamId: teamId,
                matchId: match.matchId,
                stage: window.stage
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
            return <span className="choose choose-empty"><i className="icon-emtry" /></span>;
        }
        if(result.status) {
            return <span className="choose choose-win"><i className="icon-win" /></span>;
        }
        return <span className="choose choose-lose"><i className="icon-lose" /></span>;
    };

    getVoteScore = result => {
        if(result === null) {
            return <></>;
        }
        if(result.status) {
            return <span>{`+${result.score}`}</span>;
        }
        return <span>0</span>;
    };

    render() {
        const { match, result } = this.props;
        const voteStatus = this.checkVoteOption();
        return (
            <>
                <div className="group-cell-table">
                    <div className="table-row table-row-index">
                        <div className="team-choose">
                            <EqualHeight>
                                <div className={`minigame-team home-team ${voteStatus === 1 ? 'active' : ''}`} onClick={() => this.handleVote(match.homeTeam.teamId)}>
                                    <TeamIcon className="soccer-team-logo" src={CommonFunctions.getTeamIcon(match.homeTeam)} />
                                    <EqualHeightElement name="Name">
                                        <div className="team-name">
                                            {CommonFunctions.getTeamName(match.homeTeam)}
                                        </div>
                                    </EqualHeightElement>
                                </div>
                                <div className={`minigame-team ${voteStatus === 0 ? 'active' : ''}`} onClick={() => this.handleVote(0)} >
                                    <i className="icon-minigame_draw" />
                                    <EqualHeightElement name="Name">
                                        <div className="team-name"> Hòa </div>
                                    </EqualHeightElement>
                                </div>

                                <div className={`minigame-team away-team ${voteStatus === 2 ? 'active' : ''}`} onClick={() => this.handleVote(match.awayTeam.teamId)}>
                                    <TeamIcon className="soccer-team-logo" src={CommonFunctions.getTeamIcon(match.awayTeam)} />
                                    <EqualHeightElement name="Name">
                                        <div className="team-name">
                                            {CommonFunctions.getTeamName(match.awayTeam)}
                                        </div>
                                    </EqualHeightElement>
                                </div>
                            </EqualHeight>
                            
                        </div> 
                    </div>
                    <div className="table-row">{this.getTeamVote()}</div>
                    <div className="table-row">{this.getVoteResult(result)}</div>
                    <div className="table-row">{this.getVoteScore(result)}</div>
                </div>
            </>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { votes, teams } = state;
    return { votes, teams };
};

export default connect(mapStateToProps, mapDispatchToProps)(Item);