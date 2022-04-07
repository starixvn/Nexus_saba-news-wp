import React, { PureComponent } from 'react';
import moment from 'moment';
import { useNavigate } from 'react-router-dom';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import Site from '@jsroot/common/site';
import DateTimeService from '@jsroot/common/dateTimeService';
import LangHelper from '@jsroot/common/langHelper';

export const withNavigation = Component => {
    return props => <Component {...props} navigate={useNavigate()} />;
}

class WeekBar extends PureComponent {
    constructor(props) {
        super(props);

        this.mobileMaxWidth = 768;

        this.state = {
            isMobile: false
        };
    }

    componentDidMount() {
        window.addEventListener('resize', this.handleResize);
    }

    componentWillUnmount() {
        window.removeEventListener('resize', this.handleResize);
    }

    handleResize = () => {
        const dateRanges = [...this.props.dateRanges];
        if (window.innerWidth <= this.mobileMaxWidth && dateRanges.length === 5) {
            dateRanges.shift();
            dateRanges.shift();
            this.props.setDateRanges(dateRanges);
        } else {
            if (window.innerWidth > this.mobileMaxWidth && dateRanges.length === 3) {
                dateRanges.unshift(moment(dateRanges[0]).subtract(1, 'days'));
                dateRanges.unshift(moment(dateRanges[0]).subtract(1, 'days'));
                this.props.setDateRanges(dateRanges);
            }
        }
    }

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
        window.isLiveMatchPage = false;
        this.props.setSelectedDate(date);
        this.props.onClick(date);
        this.props.navigate(Site.resolveClientUrl(`lich-thi-dau/bong-da/${date}`));
    };

    render() {
        const { today, minDate, maxDate, selectedDate, dateRanges } = this.props;

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
                        return (
                            <div key={index} className={`week-date ${moment(selectedDate).isSame(date) && !window.isLiveMatchPage ? 'active' : ''}`}
                                onClick={() => this.onSelectDate(item)}
                            >
                                <div className="day-of-week">{date.isSame(today) ? LangHelper.getResource('Today') : date.format('dddd')}</div>
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
    const { today, minDate, maxDate, selectedDate, dateRanges } = state;
    return { today, minDate, maxDate, selectedDate, dateRanges };
};

export default connect(mapStateToProps, mapDispatchToProps)(WeekBarComponent);