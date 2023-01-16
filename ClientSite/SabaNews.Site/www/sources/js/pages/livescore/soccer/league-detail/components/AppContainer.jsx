import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { Tabs, Tab } from 'react-bootstrap';
import moment from 'moment';
import { ActionCreators } from '../actions';
import FavoriteLeagues from '@jsroot/components/FavoriteLeagues';
import FavoriteMatches from '@jsroot/components/FavoriteMatches';
import Breadscrum from '@jsroot/components/Breadscrum';
import SignalR from './SignalR';
import League from './League';
import MatchGrid from './match-grid/index';
import General from './general/Index';
import SeasonStandings from './standings/index';
import SkeletonLeague from './SkeletonLeague';
import SkeletonGrid from './match-grid/SkeletonGrid';
import withNavigation from '@jsroot/components/withNavigation';
import Site from '@jsroot/common/site';
import DateTimeService from '@jsroot/common/dateTimeService';
import CommonFunctions from '@jsroot/common/common-functions';

moment.updateLocale('vi', {
    weekdays: ['Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy']
});
moment.locale('vi');

export class AppContainer extends Component {
    constructor(props) {
        super(props);

        const tabs = ['general', 'fixtures', 'results', 'standings'];
        let selectedTab = window.getMatchByStageParams.selectedTab.toLowerCase(),
            selectedResultsTab = false,
            selectedStandingsTab = false,
            selectedFixtureTab = false;
        if (selectedTab.length === 0 || tabs.findIndex(tab => tab === selectedTab) === -1) {
            selectedTab = 'general';
        }

        switch (selectedTab) {
            case 'fixtures':
                selectedFixtureTab = true;
                break;
            case 'results':
                selectedResultsTab = true;
                break;
            case 'standings':
                selectedStandingsTab = true;
                break;
        }

        this.state = {
            selectedTab,
            selectedFixtureTab,
            selectedResultsTab,
            selectedStandingsTab,
            flagToggleFavLeagueIcon: false
        };
    }

    componentDidMount() {
        this.props.getFavLeagues();
        this.props.getLeagues();
        this.getMatches();
    }

    getMatches = () => {
        const params = {
            ...window.getMatchByStageParams,
            timezone: DateTimeService.getClientTimezone()
        };
        this.props.getMatchesBySeason(params);
    };

    handleSelectTab = key => {
        let { selectedFixtureTab, selectedResultsTab, selectedStandingsTab } = this.state;

        switch (key) {
            case 'fixtures':
                if (!selectedFixtureTab) {
                    selectedFixtureTab = true;
                }
                break;
            case 'results':
                if (!selectedResultsTab) {
                    selectedResultsTab = true;
                }
                break;
            case 'standings':
                if (!selectedStandingsTab) {
                    selectedStandingsTab = true;
                }
                break;
        }

        this.setState({ selectedTab: key, selectedFixtureTab, selectedResultsTab, selectedStandingsTab });

        const params = window.getMatchByStageParams;
        const leagueName = Site.toSlug(CommonFunctions.getLeagueName(this.props.league));
        const url = Site.resolveClientUrl(`lich-thi-dau/bong-da/${leagueName}/${params.seasonId}/${params.date}/${params.leagueId}/${key}`);
        this.props.navigate(url);
    };

    renderSkeletonGrid = (tab, match) => {
        return tab && match === null && <SkeletonGrid  />
    }

    renderTabs = () => {
        const { selectedTab, selectedFixtureTab, selectedResultsTab, selectedStandingsTab } = this.state;
        const { league, stages, preMatches, postMatches } = this.props;
        return (
            <Tabs activeKey={selectedTab} id="livescore-tab-league-detail"
                className="live-score-nav"
                onSelect={this.handleSelectTab}
            >
                <Tab eventKey="general" title="Tổng hợp">
                    {
                        this.renderSkeletonGrid(true, preMatches)
                    }
                    {
                        (preMatches !== null || postMatches !== null) &&
                        <General  league={league} stages={stages} preMatches={preMatches} postMatches={postMatches} handleSelectTab={this.handleSelectTab}/>
                    }
                </Tab>
                <Tab eventKey="fixtures" title="Lịch thi đấu">
                    {
                        this.renderSkeletonGrid(selectedFixtureTab, preMatches)
                    }
                    {
                        selectedFixtureTab && preMatches !== null && <MatchGrid sortAsc league={league} stages={stages} matches={preMatches} />
                    }
                </Tab>
                <Tab eventKey="results" title="Kết quả">
                    {
                        this.renderSkeletonGrid(selectedResultsTab, postMatches)
                    }
                    {
                        selectedResultsTab && postMatches !== null &&
                        <MatchGrid sortAsc={false} league={league} stages={stages} matches={postMatches} />
                    }
                </Tab>
                <Tab eventKey="standings" title="BXH" disabled={!league || !league.hasSeasonStandings}>
                    {
                        selectedStandingsTab &&
                        <SeasonStandings />
                    }
                </Tab>
            </Tabs>);
    };

    onUpdateFavLeague = () => {
        this.props.setFavLeagues(CommonFunctions.getFavLeagues());
    };

    onToggleFavLeaguesIcon = () => {
        this.setState({flagToggleFavLeagueIcon: !this.state.flagToggleFavLeagueIcon});
    };

    render() {
        const { favLeagues, league, leagues } = this.props;
        if (league !== null) {
            document.title = CommonFunctions.getLeagueName(league);
        }

        return (
            <div className="container livescore-container">
                <SignalR getMatches={this.getMatches} />
                <div className="livescore-soccer">
                    <div className="livescore-soccer-main">
                        {
                            league === null && <SkeletonLeague />
                        }
                        {
                            league !== null &&
                            <>
                                <Breadscrum currentPage={CommonFunctions.getLeagueName(league)} currentPageUrl={window.location.href} />
                                <League league={league} flagToggleFavLeagueIcon={this.state.flagToggleFavLeagueIcon} onUpdateFavLeague={this.onUpdateFavLeague}/>
                            </>
                        }
                        {this.renderTabs()}
                    </div>
                    <div className="livescore-soccer-extension">
                        <FavoriteMatches />
                        <FavoriteLeagues searchLeagues={leagues} leagues={favLeagues} onToggleIcon={this.onToggleFavLeaguesIcon}/>
                    </div>
                </div>
            </div>
        );
    }
}

const AppContainerWithNavigation = withNavigation(AppContainer);

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { favLeagues, league, stages, preMatches, postMatches, leagues } = state;
    return { favLeagues, league, stages, preMatches, postMatches, leagues };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainerWithNavigation);