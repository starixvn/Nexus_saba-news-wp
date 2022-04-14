import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import SignalR from './SignalR';
import FunctionBar from './FunctionBar';
import { ActionCreators } from '../actions';
import MatchGenetalInfo from './MatchGeneralInfo';

export class AppContainer extends Component {
    constructor(props) {
        super(props);
    }
    componentDidMount() {
        this.props.getMatchDetail();
    }

    render() {
        if (!this.props.match || !this.props.match.generalInfo ) {
            return (
                <h1 className="livescore-soccer">Trận đấu bạn tìm không có</h1>
            );
        }
        return (
            <>
                <SignalR />
                <div className="livescore-soccer">
                    <MatchGenetalInfo match = {this.props.match} isLiveMatch = {this.props.isLiveMatch} />
                    <FunctionBar />
                </div>
            </>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { match, isLiveMatch } = state;
    return {
        match,
        isLiveMatch
    };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);