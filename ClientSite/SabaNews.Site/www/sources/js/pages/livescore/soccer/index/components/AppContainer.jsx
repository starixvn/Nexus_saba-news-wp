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

export class AppContainer extends Component {
    constructor(props) {
        super(props);

        this.mobileMaxWidth = 768;
    }

    componentDidMount() {
        this.props.setDateRanges(this.getDateRanges(this.props.selectedDate));
        this.props.getMajorLeagues();
        this.getMatches(this.props.selectedDate);
    }

    getDateRanges = selectedDate => {
        const dates = [];
        selectedDate = moment(selectedDate);
        const minDate = moment(this.props.minDate);
        const maxDate = moment(this.props.maxDate);
        let totalItems = window.innerWidth <= this.mobileMaxWidth ? 3 : 5;

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
                totalItems = window.innerWidth <= this.mobileMaxWidth ? 2 : 3;
                for (let i = 1; i < totalItems; i++) {
                    dates.push(DateTimeService.formatDateTime(selectedDate.clone().add(i, 'days'), 'YYYY-MM-DD'));
                    dates.unshift(DateTimeService.formatDateTime(selectedDate.clone().subtract(i, 'days'), 'YYYY-MM-DD'));
                }
            }
        }

        return dates;
    }

    getMatches = selectedDate => {
        if (window.isLiveMatchPage) {
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
            <div className="container">
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
    const { minDate, maxDate, selectedDate, majorLeagues, matches } = state;
    return { minDate, maxDate, selectedDate, majorLeagues, matches };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);