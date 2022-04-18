import React, { PureComponent } from 'react';
import moment from 'moment';
import { useNavigate } from 'react-router-dom';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import Site from '@jsroot/common/site';
import DateTimeService from '@jsroot/common/dateTimeService';

export const withNavigation = Component => {
    return props => <Component {...props} navigate={useNavigate()} />;
}

class WeekBar extends PureComponent {
    onPrev = () => {
        const dateRanges = [...this.props.dateRanges];
        dateRanges.pop();
        dateRanges.unshift(DateTimeService.formatDateTime(moment(dateRanges[0]).subtract(1, 'days'), 'YYYY-MM-DD'));
        this.props.setDateRanges(dateRanges);
    };

    onNext = () => {
        const dateRanges = [...this.props.dateRanges];
        dateRanges.shift();
        dateRanges.push(DateTimeService.formatDateTime(moment(dateRanges[dateRanges.length - 1]).add(1, 'days'), 'YYYY-MM-DD'));
        this.props.setDateRanges(dateRanges);
    };

    onSelectDate = date => {
        window.isLivePage = false;
        this.props.setSelectedDate(date);
        this.props.onClick(date);
        this.props.navigate(Site.resolveClientUrl(`lich-thi-dau/bong-da/${date}`));
    };

    render() {
        const { isMobile, today, minDate, maxDate, selectedDate, dateRanges } = this.props;

        return (
            <div className="livescore-soccer-week-bar">
                <button className="btn-prev-date" onClick={this.onPrev}
                    disabled={dateRanges.length > 0 && moment(dateRanges[0]).isSame(minDate)}
                >
                    <i className="icon-livescore icon-chevron-left" />
                </button>
                {
                    dateRanges.map((item, index) => {
                        const date = moment(item);
                        const isActive = moment(selectedDate).isSame(date) && !window.isLivePage;
                        const isToday = date.isSame(today);
                        return (
                            <div key={index} className={`week-date ${isActive ? 'active' : ''} ${isToday ? 'today' : ''}`}
                                onClick={() => this.onSelectDate(item)}
                            >
                                <div className="day-of-week">
                                    {isMobile ? date.format('ddd') : date.format('dddd')}
                                </div>
                                <div className="date">{date.format('DD/MM')}</div>
                            </div>
                        );
                    })
                }
                <button className="btn-next-date" onClick={this.onNext}
                    disabled={dateRanges.length > 0 && moment(dateRanges[dateRanges.length - 1]).isSame(maxDate)}
                >
                    <i className="icon-livescore icon-chevron-right" />
                </button>
            </div>
        );
    }
}

const WeekBarComponent = withNavigation(WeekBar);

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { isMobile, today, minDate, maxDate, selectedDate, dateRanges } = state;
    return { isMobile, today, minDate, maxDate, selectedDate, dateRanges };
};

export default connect(mapStateToProps, mapDispatchToProps)(WeekBarComponent);