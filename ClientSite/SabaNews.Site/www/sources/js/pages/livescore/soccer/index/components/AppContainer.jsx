import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import Leagues from './Leagues';
import DateBar from './DateBar';
import SearchBar from './SearchBar';
import MatchGrid from './match-grid/index';
import SignalR from './SignalR';
import DateTimeService from '@jsroot/common/dateTimeService';

export class AppContainer extends Component {
    componentDidMount() {
        this.props.getMajorLeagues();

        if (window.isLiveMatch) {
            this.props.getLiveMatches();
        } else {
            const eventDate = window.selectedEventDate ? window.selectedEventDate : DateTimeService.getCurrentClientDateTime();
            this.props.getMatches({
                eventDate: DateTimeService.formatDateTime(eventDate, 'YYYY-MM-DD'),
                timezone: DateTimeService.getClientTimezone()
            });
        }
    }

    render() {
        let matches = this.props.matches;
        if (matches === null) {
            return null;
        }

        return (
            <div className="container">
                <SignalR />
                <div className="livescore-soccer">
                    <div className="livescore-soccer-main">
                        <DateBar matches={matches} />
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
    const { majorLeagues, matches } = state;
    return { majorLeagues, matches };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);