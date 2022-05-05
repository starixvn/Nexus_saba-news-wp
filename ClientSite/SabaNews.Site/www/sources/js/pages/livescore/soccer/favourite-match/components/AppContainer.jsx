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
import { useNavigate } from 'react-router-dom';
import Site from '@jsroot/common/site';
import queryString from 'query-string';

moment.updateLocale('vi', {
    weekdays: ['Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy']
});
moment.locale('vi');

export const withNavigation = Component => props => <Component {...props} navigate={useNavigate()} />;

export class AppContainer extends Component {
    constructor(props) {
        super(props);

        let selectedTab = '';
        const params = queryString.parse(window.location.search);
        if (params['tab']) {
            selectedTab = params['tab'].toLowerCase();
        }
        const tabs = ['tat-ca', 'truc-tiep', 'chua-dien-ra', 'da-dien-ra'];
        const selectedAllTab = false;
        let selectedLiveTab = false,
            selectedPreTab = false,
            selectedPostTab = false;
        if (selectedTab.length === 0 || tabs.findIndex(tab => tab === selectedTab) === -1) {
            selectedTab = 'tat-ca';
        }

        switch (selectedTab) {
            case 'truc-tiep':
                selectedLiveTab = true;
                break;
            case 'chua-dien-ra':
                selectedPreTab = true;
                break;
            case 'da-dien-ra':
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
        this.props.getMajorLeagues();
        this.props.getMatches();
    }

    handleSelectTab = key => {
        let { selectedAllTab, selectedLiveTab, selectedPreTab, selectedPostTab } = this.state;

        switch (key) {
            case 'tat-ca':
                if (!selectedAllTab) {
                    selectedAllTab = true;
                }
                break;
            case 'truc-tiep':
                if (!selectedLiveTab) {
                    selectedLiveTab = true;
                }
                break;
            case 'chua-dien-ra':
                if (!selectedPreTab) {
                    selectedPreTab = true;
                }
                break;
            case 'da-dien-ra':
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
        const { majorLeagues, matches, postMatches, preMatches, liveMatches } = this.props;
        return (
            <div className="container livescore-container">
                <SignalR />
                <div className="livescore-soccer">
                    <div className="livescore-soccer-main">
                        <Breadscrum currentPage = 'Trận đấu quan tâm' />
                        <div className="livescore-league-header-detail">
                            <div className="livescore-league-header-detail-group">
                                <div className="livescore-league-header-detail-icon"><i className="icon-livescore icon-live-start" /></div>
                                <div className="livescore-league-header-detail-name">
                                    Trận đấu quan tâm
                                </div>
                            </div>
                        </div>
                        <Tabs activeKey={selectedTab} id="livescore-tab-league-detail"
                            className="live-score-nav"
                            onSelect={this.handleSelectTab}
                        >
                            <Tab eventKey="tat-ca" title="Tất cả">
                                {matches && <MatchGrid isAllMatches sortAsc matches={matches} onUpdateFavouriteMatch={this.onUpdateFavouriteMatch}/>}
                            </Tab>
                            <Tab eventKey="truc-tiep" title="Trực tiếp">
                                {liveMatches && <MatchGrid sortAsc matches={liveMatches} onUpdateFavouriteMatch={this.onUpdateFavouriteMatch}/>}
                            </Tab>
                            <Tab eventKey="chua-dien-ra" title="Chưa diễn ra">
                                {preMatches && <MatchGrid sortAsc matches={preMatches} onUpdateFavouriteMatch={this.onUpdateFavouriteMatch}/>}
                            </Tab>
                            <Tab eventKey="da-dien-ra" title="Đã diễn ra">
                                {postMatches && <MatchGrid sortAsc={false} matches={postMatches} onUpdateFavouriteMatch={this.onUpdateFavouriteMatch}/>}
                            </Tab>
                        </Tabs>
                    </div>
                    <div className="livescore-soccer-extension">
                        <FavoriteMatches />
                        <FavoriteLeagues leagues={majorLeagues} />
                    </div>
                </div>
            </div>
        );
    }
}
const AppContainerComponent = withNavigation(AppContainer);

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { majorLeagues, matches, postMatches, preMatches, liveMatches } = state;
    return { majorLeagues, matches, postMatches, preMatches, liveMatches };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainerComponent);