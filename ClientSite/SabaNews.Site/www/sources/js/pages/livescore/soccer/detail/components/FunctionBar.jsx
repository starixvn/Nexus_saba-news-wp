import React, { PureComponent } from 'react';
import { useNavigate } from 'react-router-dom';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import Site from '@jsroot/common/site';
import queryString from 'query-string';
import CommonFunctions from '@jsroot/common/common-functions';

export const withNavigation = Component => props => <Component {...props} navigate={useNavigate()} />;

class FunctionBar extends PureComponent {
    constructor(props) {
        super(props);
        this.state = {
            activeTab: 'dien-bien'
        };
    }

    componentDidMount() {
        const tabStatus = this.updateTabStatus();
        const defaultTab = this.getDefaultActiveKey(tabStatus);
        this.setState({
            activeTab: defaultTab
        });
    }

    updateTabStatus = () => {
        const tabStatus = {
            enableTabSummary: true,
            enableTabStatistics: true,
            enableTabLineups: true,
            enableTabHeadtohead: true
        };
        const { generalInfo, incidents, lineup, headToHead, statistic } = this.props.match;
        if (generalInfo.isLineUp === 0) {
            tabStatus['enableTabLineups'] = false;
        }
        if (!statistic.homeStatistic || !statistic.awayStatistic || !statistic.homeStatistic.controlRate || CommonFunctions.checkPreMatchStatus(generalInfo.status)) {
            tabStatus['enableTabStatistics'] = false;
        }
        if(!headToHead || (headToHead.home.length === 0 && headToHead.away.length === 0 && headToHead.common.length === 0)) {
            tabStatus['enableTabHeadtohead'] = false;
        }
        if (!incidents || incidents.length === 0 || CommonFunctions.checkPreMatchStatus(generalInfo.status)) {
            tabStatus['enableTabSummary'] = false;
        }
        if (!lineup || !lineup.generalInfo || (lineup.home.length === 0 && lineup.away.length === 0 )) {
            tabStatus['enableTabLineups'] = false;
        }
        return tabStatus;
    };

    getDefaultActiveKey = tabStatus => {
        const mapTabStatusKey = {
            'dien-bien': 'Summary',
            'thong-ke': 'Statistics',
            'doi-hinh': 'Lineups',
            'doi-dau': 'Headtohead'
        };
        let defaultTab = '';
        const params = queryString.parse(window.location.search);
        if(params['tab']) {
            defaultTab = params['tab'].toLowerCase();
        }
        if(!defaultTab || (defaultTab && !tabStatus[`enableTab${mapTabStatusKey[defaultTab]}`])) {
            defaultTab = '';
            if(tabStatus.enableTabHeadtohead) {
                defaultTab = 'doi-dau';
            }
            if(tabStatus.enableTabLineups) {
                defaultTab = 'doi-hinh';
            }
            if(tabStatus.enableTabStatistics) {
                defaultTab = 'thong-ke';
            }
            if(tabStatus.enableTabSummary) {
                defaultTab = 'dien-bien';
            }
        }
        return defaultTab;
    };

    scrollToTab = tab =>  {
        const section = document.getElementById(tab);
        if(section) {
            const headerHeight = document.querySelector('.sticky-header').offsetHeight;
            window.scroll({
                top: section.offsetTop - headerHeight,
                left: 0,
                behavior: 'smooth'
            });
            this.setState({
                activeTab: tab
            });
            this.props.navigate(Site.resolveClientUrl(`lich-thi-dau/bong-da/chi-tiet/${window.selectedMatchId}/?tab=${tab}`));
        }
    };

    render() {
        const tabStatus = this.updateTabStatus();
        const { activeTab } = this.state;
        return (
            <>
                <div className="detail-function-bar">
                    <ul>
                        <li>
                            <a onClick={() => this.scrollToTab('dien-bien')}
                                className={`${activeTab === 'dien-bien' ? 'active' : ''} ${!tabStatus.enableTabSummary ? 'disabled': ''}`}>
                                Diễn biến
                            </a>
                        </li>
                        <li>
                            <a onClick={() => this.scrollToTab('thong-ke')}
                                className={`${activeTab === 'thong-ke' ? 'active' : ''} ${!tabStatus.enableTabStatistics ? 'disabled': ''}`}>
                                Thống kê
                            </a>
                        </li>
                        <li>
                            <a onClick={() => this.scrollToTab('doi-hinh')}
                                className={`${activeTab === 'doi-hinh' ? 'active' : ''} ${!tabStatus.enableTabLineups ? 'disabled': ''}`}>
                                Đội hình
                            </a>
                        </li>
                        <li>
                            <a onClick={() => this.scrollToTab('doi-dau')}
                                className={`${activeTab === 'doi-dau' ? 'active' : ''} ${!tabStatus.enableTabHeadtohead ? 'disabled': ''}`}>
                                Đối đầu
                            </a>
                        </li>
                    </ul>
                </div>
            </>
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
