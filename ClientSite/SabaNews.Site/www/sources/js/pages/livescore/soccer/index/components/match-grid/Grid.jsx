import React, { PureComponent } from 'react';
import InfiniteScroll from 'react-infinite-scroller';
import League from './League';

export default class Grid extends PureComponent {
    constructor(props) {
        super(props);

        this.itemPerPage = 10;
        const items = props.leagues.slice(0, 1 * this.itemPerPage);

        this.state = {
            pageIndex: 0,
            hasMore: items.length < props.leagues.length,
            leagues: items
        };
    }

    componentDidUpdate(prevProps) {
        if (this.props.leagues !== prevProps.leagues) {
            const items = this.props.leagues.slice(0, 1 * this.itemPerPage);
            this.setState({
                pageIndex: 0,
                hasMore: items.length < this.props.leagues.length,
                leagues: items
            });
        }
    }

    loadMore = pageIndex => {
        const leagues = this.props.leagues;
        const items = leagues.slice(0, pageIndex * this.itemPerPage);
        this.setState({
            pageIndex,
            hasMore: items.length < leagues.length,
            leagues: items
        });
    }

    render() {
        const { hasMore, leagues } = this.state;
        const { selectedDate, searchKey } = this.props;

        return (
            <div className="soccer-match-grid">
                <InfiniteScroll initialLoad={false}
                    pageStart={1}
                    loadMore={this.loadMore}
                    hasMore={hasMore}
                    useWindow={true}
                >
                    {
                        leagues.map((league, index) =>
                            <League key={index} selectedDate={selectedDate} searchKey={searchKey} league={league} />)
                    }
                </InfiniteScroll>
            </div>
        );
    }
}