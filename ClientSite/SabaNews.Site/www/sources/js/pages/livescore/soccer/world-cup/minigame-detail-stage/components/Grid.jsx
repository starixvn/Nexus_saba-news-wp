import React, { PureComponent } from 'react';
import Item from './Item';
import CommonFunctions from '@jsroot/common/common-functions';
import {  MatchResult, MatchStatus, WorldCup } from '@jsroot/common/constants';

export default class Grid extends PureComponent {
    constructor(props) {
        super(props);
        this.totalScore = 0;
    }

    getWinScore = stageId => {
        switch(stageId) {
            case WorldCup.FinalStage:
                return WorldCup.MinigameScore.Final;
            case WorldCup.SemifinalsStage:
                return WorldCup.MinigameScore.Semifinal;
            default:
                return WorldCup.MinigameScore.Others;
        }
    }

    checkMatchResult = (match, vote) => {
        if(match.status !== MatchStatus.Finished) {
            return null;
        }
        let status = false;
        let score = 0;
        const voteTeam = vote.teamId === 0 ? match.homeTeam.teamId : vote.teamId;
        const matchResult = CommonFunctions.getMatchResult(voteTeam, match);
        if(matchResult === MatchResult.Win) {
            if(vote.teamId !== 0) {
                score = this.getWinScore(match.stage.stageId);
                status = true;
            } else {
                status = false;
            }
        } else if(matchResult === MatchResult.Draw) {
            if(vote.teamId === 0) {
                score = this.getWinScore(match.stage.stageId);
                status = true;
            } else {
                status = false;
            }
        }
        this.totalScore += score;
        return {status, score};
    }

    render() {
        const { matches, votes } = this.props;
        return (
            <div className="soccer-match-grid-item">
                <div className="soccer-match-grid-item-body">
                {
                    matches.map((item, index) => {
                        const vote = votes.find(value => value.matchId === item.matchId);
                        return (
                            vote && <Item key={index} match={item} vote={vote} result={this.checkMatchResult(item, vote)} />
                        )
                    })
                }
                </div>
                <div className="statistic">Total: {this.totalScore}</div>
            </div>
        );
    }
}
