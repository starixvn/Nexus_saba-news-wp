import React, { PureComponent } from 'react';
import Match from './Match';
import { MatchStatus, Width1024 } from '@jsroot/common/constants';

export default class Grid extends PureComponent {
    constructor(props) {
        super(props);
        this.matchLength = props.matches.length;
        this.showItems = 8;
        this.state = {
            start: 0,
            end: this.showItems,
            isScroll: window.innerWidth <= Width1024 ? true : false
        };
    }

    componentDidMount() {
        const { matches, isFinished } = this.props;
        let { start, end } = this.state;
        const gridWidth = window.innerWidth - 40;
        this.showItems = Math.ceil(gridWidth / 260);
        if (!isFinished) {
            const postMatchIndex = [...matches].reverse().findIndex(item => item.status === MatchStatus.Finished);
            const startLastPage = matches.length - this.showItems + 1;
            if (postMatchIndex >= 0) {
                const lastIndex = matches.length - postMatchIndex;
                if (lastIndex - 3 > startLastPage) {
                    start = startLastPage;
                } else {
                    start = lastIndex - 3 > 0 ? lastIndex - 3 : 0;
                }
            }
        }
        end = start + this.showItems;
        this.setState({
            start: start,
            end: end
        });
        window.addEventListener('resize', this.handleResize);
    }

    componentWillUnmount() {
        window.removeEventListener('resize', this.handleResize);
    }

    handleResize = () => {
        const isScroll = this.state.isScroll;
        if (!isScroll && window.innerWidth <= Width1024) {
            this.setState({ isScroll: true });
        } else {
            if (isScroll && window.innerWidth > Width1024) {
                this.setState({ isScroll: false });
            }
        }
    };

    onPrev = () => {
        const { start, end } = this.state;
        if (start === 0) {
            return;
        }
        this.setState({
            start: start - 1,
            end: end - 1
        });
    };

    onNext = () => {
        const { start, end } = this.state;
        if (end === this.matchLength + 1) {
            return;
        }
        this.setState({
            start: start + 1,
            end: end + 1
        });
    };

    getGridMatches = () => {
        const { start, end, isScroll } = this.state;
        const { matches } = this.props;
        if (!isScroll) {
            return matches.slice(start, end);
        }
        return matches;
    };

    render() {
        const { league } = this.props;
        const grid = this.getGridMatches();
        return (
            <div className="wc-bg-overflow">
                <div className="wc-date-bar">
                    {
                        grid.map((item, index) =>
                            <Match key={index} match={{ ...item, league }} />
                        )
                    }
                    <div onClick={this.onNext} className="wc-date-control wc-date-control-next"><i className="icon-chevron-right"></i></div>
                    <div onClick={this.onPrev} className="wc-date-control wc-date-control-prev"><i className="icon-chevron-left"></i></div>
                </div>
            </div>
        );
    }
}