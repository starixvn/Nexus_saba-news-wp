import React, { PureComponent } from 'react';
import Match from './Match';
import DateTimeService from '@jsroot/common/dateTimeService';
import CommonFunctions from '@jsroot/common/common-functions';
import { MatchStatus } from '@jsroot/common/constants';

export default class GridItem extends PureComponent {
    constructor(props) {
        super(props);
    }

    groupMatchesByDate = matches => {
        const { isFixture } = this.props;
        const totalItems = matches.length;
        const tempMatches = [];
        for (let i = 0; i < totalItems; i++) {
            const match = matches[i];
            const startTime = DateTimeService.convertFromUTC(match.startTime);
            const key = DateTimeService.formatDateTime(startTime, 'YYYY-MM-DD');
            const item = {
                key,
                text: DateTimeService.formatDateTime(key, 'dddd, DD/MM/YYYY'),
                childs: [match]
            };
            if (tempMatches.length === 0) {
                tempMatches.push(item);
            } else {
                const index = tempMatches.findIndex(x => x.key === key);
                if (index === - 1) {
                    if(this.isShowMoreDate(isFixture, tempMatches)) {
                        tempMatches.push(item);
                    } else {
                        break;
                    }
                } else {
                    tempMatches[index].childs.push(match);
                }
            }
        }

        return tempMatches;
    };

    isShowMoreDate = (isFixture, tempMatches) => {
        let isShowMore = false;
        if(isFixture) {
            const lastItem = tempMatches[tempMatches.length - 1];
            const listMatches = lastItem.childs;
            const lastMatch = listMatches[listMatches.length - 1];
            if((CommonFunctions.isLiveMatch(lastMatch.status) || lastMatch.status === MatchStatus.Delay) && tempMatches.length < 3) {
                isShowMore = true;
            }
        }
        return isShowMore;
    };

    render() {
        const { league } = this.props;
        const matches = this.groupMatchesByDate(this.props.matches);
        return (
            <div className="league-story">
                {
                    matches.map(item => {
                        return (
                            <div className="soccer-match-grid" key={item.key}>
                                <div className="soccer-match-grid-item">
                                    <div className="soccer-match-grid-item-header">{item.text}</div>
                                    <div className="soccer-match-grid-item-body">
                                        {
                                            item.childs.map((match, key) => <Match key={key} match={{...match, league}} />)
                                        }
                                    </div>
                                </div>
                            </div>
                        );
                    })
                }
            </div>
        )
    }
}