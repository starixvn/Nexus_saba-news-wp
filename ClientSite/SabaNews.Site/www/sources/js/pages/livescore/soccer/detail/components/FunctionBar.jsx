import React, { PureComponent } from 'react';
import { useNavigate } from 'react-router-dom';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import { Tabs, Tab } from 'react-bootstrap';
import MatchSummary from './MatchSummary';
import Lineups from './Lineups';
import Statistics from './Statistics';
import HeadToHead from './HeadToHead';
import Site from '@jsroot/common/site';
import queryString from 'query-string';

export const withNavigation = Component => {
    return props => <Component {...props} navigate={useNavigate()} />;
}

class FunctionBar extends PureComponent {
    constructor(props) {
        super(props);
    }

    updateTabStatus = () => {
        let tabStatus = {
            enableTabSummary: true,
            enableTabStatistics: true,
            enableTabLineups: true,
            enableTabHeadtohead: true
        }
        const { generalInfo, incidents, lineup, headToHead, statistic } = this.props.match;
        if (generalInfo.isLineUp === 0) {
            tabStatus["enableTabLineups"] = false;
        }
        if (!statistic || !statistic.homeStatistic || !statistic.awayStatistic || !statistic.homeStatistic.controlRate || !statistic.awayStatistic.controlRate) {
            tabStatus["enableTabStatistics"] = false;
        }
        if(!headToHead || (headToHead.home.length === 0 && headToHead.away.length === 0 && headToHead.common.length === 0)) {
            tabStatus["enableTabHeadtohead"] = false;
        }
        if (!incidents || incidents.length === 0) {
            tabStatus["enableTabSummary"] = false;
        }
        if (!lineup || !lineup.generalInfo || (lineup.home.length === 0 && lineup.away.length === 0 )) {
            tabStatus["enableTabLineups"] = false;
        }
        return tabStatus;
    }

    getDefaultActiveKey = (tabStatus) => {
        let mapTabStatusKey = {
            "dien-bien": "Summary",
            "thong-ke": "Statistics",
            "doi-hinh": "Lineups",
            "doi-dau": "Headtohead"
        };
        let defaultTab = "";
        let params = queryString.parse(window.location.search);
        if(params["tab"]) {
            defaultTab = params["tab"].toLowerCase();
        }
        if(!defaultTab || (defaultTab && !tabStatus[`enableTab${mapTabStatusKey[defaultTab]}`])) {
            defaultTab = ""
            if(tabStatus.enableTabHeadtohead) {
                defaultTab = "doi-dau";
            }
            if(tabStatus.enableTabLineups) {
                defaultTab = "doi-hinh";
            }
            if(tabStatus.enableTabStatistics) {
                defaultTab = "thong-ke";
            }
            if(tabStatus.enableTabSummary) {
                defaultTab = "dien-bien";
            }
        }
        return defaultTab;
    }

    handleSelectTab = (key) => {
        this.props.navigate(Site.resolveClientUrl(`lich-thi-dau/bong-da/chi-tiet/${window.selectedMatchId}/?tab=${key}`));
    }

    render() {
        const tabStatus = this.updateTabStatus();
        let defaultTab = this.getDefaultActiveKey(tabStatus);
        return (
             <Tabs  id="soccer-match-detail-tabs" defaultActiveKey={defaultTab}  onSelect={(key) => this.handleSelectTab(key)} >
                <Tab eventKey="dien-bien" title="Diễn biến" disabled={!tabStatus.enableTabSummary}>
                    <MatchSummary match = {this.props.match}/>
                </Tab>
                <Tab eventKey="thong-ke" title="Thống kê"  disabled={!tabStatus.enableTabStatistics}>
                    <Statistics match = {this.props.match}  />
                </Tab>
                <Tab eventKey="doi-hinh" title="Đội hình" disabled={!tabStatus.enableTabLineups}>
                    <Lineups />
                </Tab>
                <Tab eventKey="doi-dau" title="Đối đầu" disabled={!tabStatus.enableTabHeadtohead}>
                    <HeadToHead />
                </Tab>
            </Tabs>
        );
    }
}

const FunctionBarComponent = withNavigation(FunctionBar);

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { match } = state;
    return { match };
};

export default connect(mapStateToProps, mapDispatchToProps)(FunctionBarComponent);
