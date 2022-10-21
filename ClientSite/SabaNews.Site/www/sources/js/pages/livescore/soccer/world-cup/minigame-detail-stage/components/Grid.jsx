import React, { PureComponent } from 'react';
import Item from './Item';
import {  WorldCup } from '@jsroot/common/constants';
import { getVoteResult } from '../../common.functions';
export default class Grid extends PureComponent {
    constructor(props) {
        super(props);
        this.totalScore = 0;
    }

    checkMatchResult = (match, vote) => {
        const result = getVoteResult(match, vote);
        this.totalScore += result !== null ? result.score : 0;
        return result;
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
