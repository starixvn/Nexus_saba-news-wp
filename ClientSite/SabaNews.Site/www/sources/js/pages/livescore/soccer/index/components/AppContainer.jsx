import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import moment from 'moment';
import { ActionCreators } from '../actions';
import FavoriteLeagues from '@jsroot/components/FavoriteLeagues';
import FavoriteMatches from '@jsroot/components/FavoriteMatches';
import DateBar from './DateBar';
import MatchGrid from './match-grid/index';
import SignalR from './SignalR';
import SkeletonGrid from './match-grid/SkeletonGrid';
import DateTimeService from '@jsroot/common/dateTimeService';
import { MobileMaxWidth } from '@jsroot/common/constants';

moment.updateLocale('vi', {
    weekdays: ['Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy'],
    weekdaysShort: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7']
});
moment.locale('vi');

export class AppContainer extends Component {
    constructor(props) {
        super(props);

        this.state = {
            isMobile: window.innerWidth <= MobileMaxWidth ? true : false
        };
    }

    componentDidMount() {
        window.addEventListener('resize', this.handleResize);

        this.props.setDateRanges(this.getDateRanges(this.props.selectedDate));
        this.props.getMajorLeagues();
        this.getMatches(this.props.selectedDate);
    }

    componentWillUnmount() {
        window.removeEventListener('resize', this.handleResize);
    }

    handleResize = () => {
        const isMobile = this.state.isMobile;
        if (!isMobile && window.innerWidth <= MobileMaxWidth) {
            this.setState({ isMobile: true });
        } else {
            if (isMobile && window.innerWidth > MobileMaxWidth) {
                this.setState({ isMobile: false });
            }
        }
    };

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
    };

    getMatches = selectedDate => {
        if (this.props.searchKey.length > 0) {
            this.props.setSearchKey('');
        }

        this.props.setMatches(null);
        this.props.getLiveMatches();
        if (!window.isLivePage) {
            this.props.getMatchesByDate({
                eventDate: selectedDate,
                timezone: DateTimeService.getClientTimezone()
            });
        }
    };

    render() {
        const isMobile = this.state.isMobile;
        const { matches, majorLeagues, liveMatches } = this.props;

        return (
            <div className="container livescore-container">
                <SignalR getMatches={this.getMatches} />
                <div className="livescore-soccer">
                    <div className="livescore-soccer-main">
                        <DateBar isMobile={isMobile} totalMatches={liveMatches.length}
                            getDateRanges={this.getDateRanges}
                            onClick={this.getMatches}
                        />
                        {
                            matches === null && <SkeletonGrid />
                        }
                        {
                            matches !== null && <MatchGrid matches={matches} />
                        }
                    </div>
                    <div className="livescore-soccer-extension">
                        <FavoriteMatches />
                        <FavoriteLeagues leagues={majorLeagues} />
                    </div>
                </div>
            </div>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const {
        minDate, maxDate, selectedDate, dateRanges,
        searchKey, majorLeagues, matches, liveMatches
    } = state;
    return {
        minDate, maxDate, selectedDate, dateRanges,
        searchKey, majorLeagues, matches, liveMatches
    };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);