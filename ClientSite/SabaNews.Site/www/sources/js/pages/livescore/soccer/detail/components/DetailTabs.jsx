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

    getTabStatus = () => {
        const loadTabStatus = {
            enableTabMatchSummary: true,
            enableTabStatistics: true,
            enableTabLineUp: true,
            enableTabH2H: true
        }
        if (this.props.match.generalInfo.status !== 50 && !this.props.isLiveMatch) {
            loadTabStatus["enableTabMatchSummary"] = false;
            loadTabStatus["enableTabStatistics"] = false;
        }
        if (this.props.match.generalInfo.isLineUp === 0) {
            loadTabStatus["enableTabLineUp"] = false;
        }
        if (!this.props.match.incidents) {
            loadTabStatus["enableTabMatchSummary"] = false;
        }
        const { homeStatistic, awayStatistic } = this.props.match.statistic;
        if (!homeStatistic || !awayStatistic) {
            loadTabStatus["enableTabStatistics"] = false;
        }
        const headToHead = this.props.match.headToHead;
        if(!headToHead || (headToHead.home.length === 0 && headToHead.away.length === 0 && headToHead.common.length === 0)) {
            loadTabStatus["enableTabH2H"] = false;
        }
        return loadTabStatus;
    }
    render() {
        const loadTabStatus = this.getTabStatus();
        return (
            <Tabs  id="soccer-match-detail-tabs">
                <Tab eventKey="MatchSummary" title="Diễn biến" disabled={!loadTabStatus.enableTabMatchSummary}>
                    <MatchSummary match = {this.props.match}/>
                </Tab>
                <Tab eventKey="Statistics" title="Thống kê"  disabled={!loadTabStatus.enableTabStatistics}>
                    <Statistics match = {this.props.match}  />
                </Tab>
                <Tab eventKey="Lineups" title="Đội hình" disabled={!loadTabStatus.enableTabLineUp}>
                    <Lineups />
                </Tab>
                <Tab eventKey="HeadToHead" title="Đối đầu" disabled={!loadTabStatus.enableTabH2H}>
                    <HeadToHead />
                </Tab>
            </Tabs>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { match, loadTabStatus } = state;
    return {
        match,
        loadTabStatus
    };
};

export default connect(mapStateToProps, mapDispatchToProps)(DetailTabs);
