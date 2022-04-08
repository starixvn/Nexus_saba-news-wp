import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import moment from 'moment';
import { ActionCreators } from '../actions';
import Leagues from './Leagues';
import DateBar from './DateBar';
import SearchBar from './SearchBar';
import MatchGrid from './match-grid/index';
import SignalR from './SignalR';
import DateTimeService from '@jsroot/common/dateTimeService';
import { MobileMaxWidth } from '@jsroot/common/constants';
moment.updateLocale('vi', {
    weekdays: ['Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy'],
    weekdaysShort: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7']
});
moment.locale('vi');

export class AppContainer extends Component {
    componentDidMount() {
        window.addEventListener('resize', this.handleResize);

        this.props.setIsMobile(window.innerWidth <= MobileMaxWidth ? true : false);
        this.props.setDateRanges(this.getDateRanges(this.props.selectedDate));
        this.props.getMajorLeagues();
        this.getMatches(this.props.selectedDate);
    }

    componentWillUnmount() {
        window.removeEventListener('resize', this.handleResize);
    }

    handleResize = () => {
        this.props.setIsMobile(window.innerWidth <= MobileMaxWidth ? true : false);
    }

    getDateRanges = selectedDate => {
        const dates = [];
        let totalItems = 5;
        selectedDate = moment(selectedDate);
        const minDate = moment(this.props.minDate);
        const maxDate = moment(this.props.maxDate);

        if (selectedDate.isSame(minDate)) {
            for (let i = 0; i < totalItems; i++) {
                dates.push(DateTimeService.formatDateTime(selectedDate.clone().add(i, 'days'), 'YYYY-MM-DD'));
            }
        } else if (selectedDate.isSame(maxDate)) {
            for (let i = 0; i < totalItems; i++) {
                dates.unshift(DateTimeService.formatDateTime(selectedDate.clone().subtract(i, 'days'), 'YYYY-MM-DD'));
            }
        } else {
            dates.push(DateTimeService.formatDateTime(selectedDate, 'YYYY-MM-DD'));
            if (selectedDate.isSame(minDate.add(1, 'days'))) {
                for (let i = 1; i < totalItems; i++) {
                    dates.push(DateTimeService.formatDateTime(selectedDate.clone().add(i, 'days'), 'YYYY-MM-DD'));
                }
            } else if (selectedDate.isSame(maxDate.subtract(1, 'days'))) {
                for (let i = 1; i < totalItems; i++) {
                    dates.unshift(DateTimeService.formatDateTime(selectedDate.clone().subtract(i, 'days'), 'YYYY-MM-DD'));
                }
            } else {
                totalItems = 3;
                for (let i = 1; i < totalItems; i++) {
                    dates.push(DateTimeService.formatDateTime(selectedDate.clone().add(i, 'days'), 'YYYY-MM-DD'));
                    dates.unshift(DateTimeService.formatDateTime(selectedDate.clone().subtract(i, 'days'), 'YYYY-MM-DD'));
                }
            }
        }

        return dates;
    }

    getMatches = selectedDate => {
        if (window.isLivePage) {
            this.props.getLiveMatches();
        } else {
            this.props.getMatches({
                eventDate: selectedDate,
                timezone: DateTimeService.getClientTimezone()
            });
        }
    };

    render() {
        const { matches } = this.props;
        if (matches === null) {
            return null;
        }

        return (
            <div className="container livescore-container">
                <SignalR />
                <div className="livescore-soccer">
                    <div className="livescore-soccer-main">
                        <DateBar
                            getDateRanges={this.getDateRanges}
                            onClick={this.getMatches}
                        />
                        <SearchBar />
                        <MatchGrid matches={matches} />
                    </div>
                    <div className="livescore-soccer-extension">
                        <Leagues leagues={this.props.majorLeagues} />
                    </div>
                </div>
            </div>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { minDate, maxDate, selectedDate, dateRanges, majorLeagues, matches } = state;
    return { minDate, maxDate, selectedDate, dateRanges, majorLeagues, matches };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);