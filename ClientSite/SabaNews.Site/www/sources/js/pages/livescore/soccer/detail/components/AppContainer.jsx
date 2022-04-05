import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import SignalR from './SignalR';
import DetailTabs from './DetailTabs';
import { ActionCreators } from '../actions';
import MatchGenetalInfo from './MatchGeneralInfo';

export class AppContainer extends Component {
    constructor(props) {
        super(props);
    }
    componentDidMount() {
        this.props.getSoccerMatchDetail();
    }

    render() {
        if (!this.props.soccerMatch || !this.props.soccerMatch.generalInfo ) {
            return (
                <h1 className="livescore-soccer">Trận đấu bạn tìm không có</h1>
            );
        }
        console.log(this.props.soccerMatch);
        return (
            <>
                <SignalR />
                <div className="livescore-soccer">
                    <MatchGenetalInfo soccerMatch = {this.props.soccerMatch} isLiveMatch = {this.props.isLiveMatch} />
                    <DetailTabs />
                </div>
            </>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { soccerMatch, isLiveMatch } = state;
    return {
        soccerMatch,
        isLiveMatch
    };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);