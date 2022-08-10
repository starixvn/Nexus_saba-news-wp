import React, { PureComponent } from 'react';
import Match from './Match';
export default class Grid extends PureComponent {
    constructor(props) {
        super(props);
        this.matchLength = props.matches.length;
        this.state = {
            start: 0,
            end: 8
        }
    }

    onPrev = () => {
        const { start, end } = this.state;
        if(start === 0) {
            return false;
        }
        this.setState({
            start: start - 1,
            end: end - 1
        });
    };

    onNext = () => {
        const { start, end } = this.state;
        if(end === this.matchLength + 1 ) {
            return false;
        }
        this.setState({
            start: start + 1,
            end: end + 1
        });
    };

    getGridMatches = () => {
        const { start, end } = this.state;
        var tmp = this.props.matches.slice(start, end);
        return tmp;
    };

    render() {
        const grid = this.getGridMatches();
        return (
            <div className="wc-date-bar">
                {
                    grid.map((item, index) =>
                        <Match key={index} match={item} />
                    )
                }
                <div onClick={this.onNext} className="wc-date-control wc-date-control-next"><i className="icon-chevron-right"></i></div>
                <div onClick={this.onPrev} className="wc-date-control wc-date-control-prev"><i className="icon-chevron-left"></i></div>
            </div>
        );
    }
}