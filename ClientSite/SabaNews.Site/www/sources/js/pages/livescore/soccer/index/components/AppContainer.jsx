import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import Leagues from './Leagues';
import DateBar from './DateBar';
import SearchBar from './SearchBar';
import MatchGrid from './match-grid/index';
import SignalR from './SignalR';
import { MatchStatus } from '@jsroot/common/constants';

export class AppContainer extends Component {
    componentDidMount() {
        this.props.getMatches({ eventDate: window.selectedEventDate });
    }

    render() {
        let matches = this.props.matches;
        if (window.isLiveMatch) {
            matches = matches.filter(match => match.status === MatchStatus.FirstHalf
                || match.status === MatchStatus.SecondHalf
                || match.status === MatchStatus.Overtime
                || match.status === MatchStatus.PenaltyShootout);
        }

        return (
            <>
                <SignalR />
                <div className="livescore-soccer">
                    <div className="livescore-soccer-main">
                        <DateBar matches={matches} />
                        <SearchBar />
                        <MatchGrid matches={matches} />
                    </div>
                    <div className="livescore-soccer-right">
                        <Leagues />
                    </div>
                </div>
            </>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { matches } = state;
    return { matches };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);