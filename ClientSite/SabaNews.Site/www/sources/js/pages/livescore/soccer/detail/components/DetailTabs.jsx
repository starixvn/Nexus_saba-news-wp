import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import { Tabs, Tab } from 'react-bootstrap';
import MatchSummary from './MatchSummary';
import Lineups from './Lineups';
import Statistics from './Statistics';
import HeadToHead from './HeadToHead';

export class DetailTabs extends Component {
    constructor(props) {
        super(props);
    }

    componentDidMount() {
        if (this.props.soccerMatch.generalInfo.status !== 50 && !this.props.isLiveMatch) {
            const loadStatus = { ...this.props.loadTabStatus };
            loadStatus["enableTabMatchSummary"] = false;
            loadStatus["enableTabStatistics"] = false;
            this.props.setloadTabStatus(loadStatus)
        }
        if (this.props.soccerMatch.generalInfo.isLineUp === 0) {
            const status = { ...this.props.loadTabStatus };
            status["enableTabLineUp"] = false;
            this.props.setloadTabStatus(status)
        }
    }
    render() {
        const loadTabStatus = this.props.loadTabStatus;
        return (
            <Tabs  id="soccer-match-detail-tabs">
                <Tab eventKey="MatchSummary" title="Diễn biến" disabled={!loadTabStatus.enableTabMatchSummary}>
                    <MatchSummary soccerMatch = {this.props.soccerMatch}/>
                </Tab>
                <Tab eventKey="Statistics" title="Thống kê"  disabled={!loadTabStatus.enableTabStatistics}>
                    <Statistics soccerMatch = {this.props.soccerMatch}  />
                </Tab>
                <Tab eventKey="Lineups" title="Đội hình" disabled={!loadTabStatus.enableTabLineUp}>
                    <Lineups />
                </Tab>
                <Tab eventKey="HeadToHead" title="Đối đầu" disabled={!loadTabStatus.enableTabLineUp}>
                    <HeadToHead />
                </Tab>
            </Tabs>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { soccerMatch, loadTabStatus } = state;
    return {
        soccerMatch,
        loadTabStatus
    };
};

export default connect(mapStateToProps, mapDispatchToProps)(DetailTabs);
