import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import Leagues from './Leagues';
import DateBar from './DateBar';
import SearchBar from './SearchBar';
import Matches from './Matches';
import LiveMatch from './LiveMatch';
import SignalR from './SignalR';
import { ActionCreators } from '../actions';

export class AppContainer extends Component {
    componentDidMount() {
        this.props.getMatches({ eventDate: window.selectedEventDate });
    }

    render() {
        return (
            <>
                <SignalR />
                <div className="livescore-soccer">
                    <div className="livescore-soccer-main">
                        <DateBar />
                        <SearchBar />
                        {
                            !window.isLiveMatch &&
                            <Matches matches={this.props.matches} />
                        }
                        {
                            window.isLiveMatch &&
                            <LiveMatch />
                        }
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
    return {
        matches
    };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);