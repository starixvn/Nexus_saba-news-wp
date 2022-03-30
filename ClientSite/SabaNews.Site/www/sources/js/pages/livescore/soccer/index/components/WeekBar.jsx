import React, { PureComponent } from 'react';
import moment from 'moment';
import Site from '@jsroot/common/site';

export default class WeekBar extends PureComponent {
    constructor(props) {
        super(props);

        this.state = {
            dateRanges: this.getDateRanges()
        };
    }

    getDateRanges = () => {
        const dates = [];
        const date = moment(window.selectedEventDate);
        const minDate = moment(this.props.minDate);
        const maxDate = moment(this.props.maxDate);

        if (date.isSame(minDate)) {
            for (let i = 0; i < 5; i++) {
                dates.push(moment(date).add(i, 'days'));
            }
        } else if (date.isSame(maxDate)) {
            for (let i = 0; i < 5; i++) {
                dates.unshift(moment(date).subtract(i, 'days'));
            }
        } else {
            dates.push(date);
            if (date.isSame(minDate.add(1, 'days'))) {
                for (let i = 1; i < 5; i++) {
                    dates.push(moment(date).add(i, 'days'));
                }
            } else if (date.isSame(maxDate.subtract(1, 'days'))) {
                for (let i = 1; i < 5; i++) {
                    dates.unshift(moment(date).subtract(i, 'days'));
                }
            } else {
                for (let i = 1; i < 3; i++) {
                    dates.push(moment(date).add(i, 'days'));
                    dates.unshift(moment(date).subtract(i, 'days'));
                }
            }
        }

        return dates;
    }

    onPrev = () => {
        const dateRanges = [...this.state.dateRanges];
        dateRanges.pop();
        dateRanges.unshift(moment(dateRanges[0]).subtract(1, 'days'));
        this.setState({ dateRanges });
    };

    onNext = () => {
        const dateRanges = [...this.state.dateRanges];
        dateRanges.shift();
        dateRanges.push(moment(dateRanges[3]).add(1, 'days'));
        this.setState({ dateRanges });
    };

    render() {
        const dateRanges = this.state.dateRanges;
        const date = moment(window.selectedEventDate);
        const minDate = moment(this.props.minDate);
        const maxDate = moment(this.props.maxDate);

        return (
            <div className="livescore-soccer-week-bar">
                <button className="btn-prev-date" onClick={this.onPrev} disabled={dateRanges.length > 0 && dateRanges[0].isSame(minDate)}>
                    <i className="icon-livescore icon-chevron-left" />
                </button>
                {
                    this.state.dateRanges.map((item, index) => {
                        return (
                            <div key={index} className={`week-date ${date.isSame(item) && !window.isLiveMatch ? 'active' : ''}`}
                                onClick={() => window.location = Site.resolveClientUrl(`lich-thi-dau/bong-da/${item.format('YYYY-MM-DD')}`)}
                            >
                                <div className="day-of-week">{item.format('dddd')}</div>
                                <div className="date">{item.format('DD/MM')}</div>
                            </div>
                        );
                    })
                }
                <button className="btn-next-date" onClick={this.onNext} disabled={dateRanges.length > 0 && dateRanges[4].isSame(maxDate)}>
                    <i className="icon-livescore icon-chevron-right" />
                </button>
            </div>
        );
    }
}