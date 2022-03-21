import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import Leagues from './Leagues';
import DateBar from './DateBar';
import SearchBar from './SearchBar';
import Events from './Events';
import LiveMatch from './LiveMatch';
import SignalR from './SignalR';
import { ActionCreators } from '../actions';

export class AppContainer extends Component {
    constructor(props) {
        super(props);
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
                            <Events soccerMatches={this.props.soccerMatches} />
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
    const { soccerMatches } = state;
    return {
        soccerMatches
    };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);