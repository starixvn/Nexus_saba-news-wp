import React, { Fragment, PureComponent } from 'react';
import Item from './Item';
import { getVoteResult } from '../../common.functions';
export default class Grid extends PureComponent {
    constructor(props) {
        super(props);
    }

    checkMatchResult = (match, vote) => {
        const result = getVoteResult(match, vote);
        return result;
    };

    render() {
        const { matches, votes } = this.props;
        let totalScore = 0;
        return (
            <div className="soccer-match-grid-item">
                <div className="soccer-match-grid-item-body">
                {
                    matches.map((item, index) => {
                        const vote = votes.find(value => value.matchId === item.matchId);
                        let component = <Fragment />
                        if(vote) {
                            const result = this.checkMatchResult(item, vote);
                            totalScore +=  result !== null ? result.score : 0;
                            component = <Item key={index} match={item} vote={vote} result={result} />
                        }
                        return (
                            component
                        )
                    })
                }
                </div>
                <div className="statistic">Total: {totalScore}</div>
            </div>
        );
    }
}
