import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import { Tabs, Tab } from 'react-bootstrap';
import FavoriteLeagues from '@jsroot/components/FavoriteLeagues';
import FavoriteMatches from '@jsroot/components/FavoriteMatches';
import Breadscrum from '@jsroot/components/Breadscrum';
import MatchGrid from './MatchGrid';
import moment from 'moment';
import SignalR from './SignalR';
import withNavigation from '@jsroot/components/withNavigation';
import Site from '@jsroot/common/site';
import queryString from 'query-string';

moment.updateLocale('vi', {
    weekdays: ['Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy']
});
moment.locale('vi');

export class AppContainer extends Component {
    constructor(props) {
        super(props);
        this.tabsParam = {
            all: 'tat-ca',
            live: 'truc-tiep',
            pre: 'chua-dien-ra',
            post: 'da-dien-ra'
        };
        let selectedTab = '';
        const params = queryString.parse(window.location.search);
        if (params['tab']) {
            selectedTab = params['tab'].toLowerCase();
        }
        const tabs = [this.tabsParam.all, this.tabsParam.live, this.tabsParam.pre, this.tabsParam.post];
        const selectedAllTab = false;
        let selectedLiveTab = false,
            selectedPreTab = false,
            selectedPostTab = false;
        if (selectedTab.length === 0 || tabs.findIndex(tab => tab === selectedTab) === -1) {
            selectedTab = this.tabsParam.all;
        }

        switch (selectedTab) {
            case this.tabsParam.live:
                selectedLiveTab = true;
                break;
            case this.tabsParam.pre:
                selectedPreTab = true;
                break;
            case this.tabsParam.post:
                selectedPostTab = true;
                break;
        }
        this.state = {
            selectedTab,
            selectedAllTab,
            selectedLiveTab,
            selectedPreTab,
            selectedPostTab
        };
    }
    componentDidMount() {
        this.props.getMatches();
        this.props.getFavLeagues();
        this.props.getLeagues();
    }

    handleSelectTab = key => {
        let { selectedAllTab, selectedLiveTab, selectedPreTab, selectedPostTab } = this.state;

        switch (key) {
            case this.tabsParam.all:
                if (!selectedAllTab) {
                    selectedAllTab = true;
                }
                break;
            case this.tabsParam.live:
                if (!selectedLiveTab) {
                    selectedLiveTab = true;
                }
                break;
            case this.tabsParam.pre:
                if (!selectedPreTab) {
                    selectedPreTab = true;
                }
                break;
            case this.tabsParam.post:
                if (!selectedPostTab) {
                    selectedPostTab = true;
                }
                break;
        }

        this.setState({ selectedTab: key, selectedAllTab, selectedLiveTab, selectedPreTab, selectedPostTab });

        const url = Site.resolveClientUrl(`lich-thi-dau/bong-da/tran-dau-quan-tam?tab=${key}`);
        this.props.navigate(url);
    };

    onUpdateFavouriteMatch = matchId => {
        this.props.setFavouriteMatches({matchId: matchId});
    };

    render() {
        const { selectedTab } = this.state;
        const { favLeagues, matches, postMatches, preMatches, liveMatches, leagues } = this.props;
        return (
            <div className="container livescore-container">
                <SignalR />
                <div className="livescore-soccer">
                    <div className="livescore-soccer-main">
                        <Breadscrum currentPage = 'Trận đấu quan tâm' currentPageUrl={window.location.href} />
                        <div className="livescore-league-header-detail">
                            <div className="livescore-league-header-detail-group">
                                <div className="livescore-league-header-detail-icon">
                                    <i className="icon-livescore icon-live-start favoroute-icon" />
                                </div>
                                <div className="livescore-league-header-detail-name">
                                    Trận đấu quan tâm
                                </div>
                            </div>
                        </div>
                        <Tabs activeKey={selectedTab} id="livescore-tab-league-detail"
                            className="live-score-nav"
                            onSelect={this.handleSelectTab}
                        >
                            <Tab eventKey={this.tabsParam.all} title="Tất cả">
                                {
                                    matches &&
                                    <MatchGrid isAllMatches sortAsc matches={matches} onUpdateFavouriteMatch={this.onUpdateFavouriteMatch} />
                                }
                            </Tab>
                            <Tab eventKey={this.tabsParam.live} title="Trực tiếp">
                                {
                                    liveMatches &&
                                    <MatchGrid sortAsc matches={liveMatches} onUpdateFavouriteMatch={this.onUpdateFavouriteMatch} />
                                }
                            </Tab>
                            <Tab eventKey={this.tabsParam.pre} title="Chưa diễn ra">
                                {
                                    preMatches &&
                                    <MatchGrid sortAsc matches={preMatches} onUpdateFavouriteMatch={this.onUpdateFavouriteMatch} />
                                }
                            </Tab>
                            <Tab eventKey={this.tabsParam.post} title="Đã diễn ra">
                                {
                                    postMatches &&
                                    <MatchGrid sortAsc={false} matches={postMatches} onUpdateFavouriteMatch={this.onUpdateFavouriteMatch} />
                                }
                            </Tab>
                        </Tabs>
                    </div>
                    <div className="livescore-soccer-extension">
                        <FavoriteMatches />
                        <FavoriteLeagues searchLeagues={leagues} leagues={favLeagues} />
                    </div>
                </div>
            </div>
        );
    }
}
const AppContainerWithNavigation = withNavigation(AppContainer);

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { favLeagues, matches, postMatches, preMatches, liveMatches, leagues } = state;
    return { favLeagues, matches, postMatches, preMatches, liveMatches, leagues };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainerWithNavigation);