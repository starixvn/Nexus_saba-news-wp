import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { Tabs, Tab } from 'react-bootstrap';
import SignalR from './SignalR';
import MatchSummary from './MatchSummary';
import Lineups from './Lineups';
import Statistics from './Statistics';
import { ActionCreators } from '../actions';
import MatchGenetalInfo from './MatchGeneralInfo';

export class AppContainer extends Component {
    constructor(props) {
        super(props);
        this.state = {
            isDisabledTabMatchSummary: false,
            isDisabledTabStatistics: false,
            isDisabledTabLineUp: false
        }
    }
    componentDidMount() {
        this.props.getSoccerMatchDetail()
        .then(() => {
            if (this.props.soccerMatch.generalInfo.status !== 50 && !this.props.isLiveMatch) {
                this.setState({
                    isDisabledTabMatchSummary: true,
                    isDisabledTabStatistics: true
                });
            }
            if (this.props.soccerMatch.generalInfo.isLineUp === 0) {
                this.setState({
                    isDisabledTabLineUp: true
                });
            }
        });
    }

    render() {
        if (!this.props.soccerMatch || !this.props.soccerMatch.generalInfo ) {
            return (
                <h1 className="livescore-soccer">Trận đấu bạn tìm không có</h1>
            );
        }
        return (
            <>
                <SignalR />
                <div className="livescore-soccer">
                    <MatchGenetalInfo soccerMatch = {this.props.soccerMatch} isLiveMatch = {this.props.isLiveMatch} />
                    <Tabs  id="soccer-match-detail-tabs">
                        <Tab eventKey="MatchSummary" title="Tóm tắt Trận đấu" disabled={this.state.isDisabledTabMatchSummary}>
                            <MatchSummary soccerMatch = {this.props.soccerMatch}/>
                        </Tab>
                        <Tab eventKey="Statistics" title="Thống kê"  disabled={this.state.isDisabledTabStatistics}>
                            <Statistics soccerMatch = {this.props.soccerMatch}  />
                        </Tab>
                        <Tab eventKey="Lineups" title="Đội hình" disabled={this.state.isDisabledTabLineUp}>
                            <Lineups />
                        </Tab>
                    </Tabs>
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