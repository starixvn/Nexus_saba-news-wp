import React, { PureComponent } from 'react';
import InfiniteScroll from 'react-infinite-scroller';
import Match from '../../../index/components/match-grid/Match';

export default class Grid extends PureComponent {
    constructor(props) {
        super(props);

        this.batchSize = 50;

        const matches = props.matches;
        const groupIndex = this.getGroupIndex(0) + 1;
        const items = matches.slice(0, groupIndex);
        this.state = ({
            groupIndex,
            pageIndex: 0,
            hasMore: groupIndex < matches.length,
            matches: items
        });
    }

    getGroupIndex = currentIndex => {
        const matches = this.props.matches;
        const totalItems = matches.length;
        let count = 0;
        let index = currentIndex;
        for (index; index < totalItems; index++) {
            for (const child of matches[index].childs) {
                count += child.key ? child.childs.length : 1;
            }

            if (count >= this.batchSize) {
                break;
            }
        }

        return index;
    };

    loadMore = pageIndex => {
        const matches = this.props.matches;
        const groupIndex = this.getGroupIndex(this.state.groupIndex) + 1;
        const items = matches.slice(0, groupIndex);
        this.setState({
            groupIndex,
            pageIndex,
            hasMore: groupIndex < matches.length,
            matches: items
        });
    }

    render() {
        const { hasMore, matches } = this.state;
        const { searchKey } = this.props;

        return (
            <InfiniteScroll initialLoad={false}
                pageStart={1}
                loadMore={this.loadMore}
                hasMore={hasMore}
                useWindow={true}
            >
                {
                    matches.map(item => {
                        return (
                            <div className="soccer-match-grid" key={item.key}>
                                <div className="soccer-match-grid-item">
                                    <div className="soccer-match-grid-item-header">{item.text}</div>
                                    <div className="soccer-match-grid-item-body">
                                        {
                                            item.childs.map(child => {
                                                if (child.key) {
                                                    return (
                                                        <div className="group-match-item" key={child.key}>
                                                            <div className="group-match-item-slogan">{child.text}</div>
                                                            {
                                                                child.childs.map(match => <Match key={match.matchId} searchKey={searchKey} match={match} />)
                                                            }
                                                        </div>
                                                    );
                                                } else {
                                                    return <Match key={child.matchId} searchKey={searchKey} match={child} />;
                                                }
                                            })
                                        }
                                    </div>
                                </div>
                            </div>
                        );
                    })
                }
            </InfiniteScroll>
        );
    }
}