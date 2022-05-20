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
    static defaultProps = {
        selectedDate: '',
        searchKey: '',
        matches: null,
        majorLeagues: [],
        totalLiveMatches: 0,
        getMajorLeagues: function () {
            // nothing
        },
        getMatchesByDate: function () {
            // nothing
        },
        getLiveMatches: function () {
            // nothing
        },
        setDateRanges: function () {
            // nothing
        },
        setMatches: function () {
            // nothing
        }
    };

    constructor(props) {
        super(props);

        this.state = {
            isMobile: window.innerWidth <= MobileMaxWidth ? true : false
        };
    }

    componentDidMount() {
        window.addEventListener('resize', this.handleResize);

        this.props.setDateRanges(this.getDateRanges(this.props.selectedDate));
        this.props.getFavLeagues();
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
        const shortDateFormat = 'YYYY-MM-DD';

        if (selectedDate.isSame(minDate) || selectedDate.isSame(minDate.clone().add(1, 'days'))) {
            for (let i = 0; i < totalItems; i++) {
                dates.push(DateTimeService.formatDateTime(minDate.clone().add(i, 'days'), shortDateFormat));
            }
        } else if (selectedDate.isSame(maxDate) || selectedDate.isSame(maxDate.clone().subtract(1, 'days'))) {
            for (let i = 0; i < totalItems; i++) {
                dates.unshift(DateTimeService.formatDateTime(maxDate.clone().subtract(i, 'days'), shortDateFormat));
            }
        } else {
            totalItems = 3;
            dates.push(DateTimeService.formatDateTime(selectedDate, shortDateFormat));
            for (let i = 1; i < totalItems; i++) {
                dates.push(DateTimeService.formatDateTime(selectedDate.clone().add(i, 'days'), shortDateFormat));
                dates.unshift(DateTimeService.formatDateTime(selectedDate.clone().subtract(i, 'days'), shortDateFormat));
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

    onToggleFavLeaguesIcon = () => {
        this.props.setFlagToggleFavLeagueIcon(!this.props.flagToggleFavLeagueIcon);
    };

    render() {
        const isMobile = this.state.isMobile;
        const { matches, favLeagues, totalLiveMatches } = this.props;
        return (
            <div className="container livescore-container">
                <SignalR getMatches={this.getMatches} />
                <div className="livescore-soccer">
                    <div className="livescore-soccer-main">
                        <DateBar isMobile={isMobile} totalMatches={totalLiveMatches}
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
                        <FavoriteLeagues leagues={favLeagues} onToggleIcon={this.onToggleFavLeaguesIcon}/>
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
        searchKey, favLeagues, matches, totalLiveMatches,flagToggleFavLeagueIcon
    } = state;
    return {
        minDate, maxDate, selectedDate, dateRanges,
        searchKey, favLeagues, matches, totalLiveMatches,flagToggleFavLeagueIcon
    };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);