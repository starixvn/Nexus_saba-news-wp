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

class AppContainer extends Component {
    constructor(props) {
        super(props);

        const tabs = ['fixtures', 'results', 'standings'];
        let selectedTab = window.getMatchByStageParams.selectedTab.toLowerCase(),
            selectedResultsTab = false,
            selectedStandingsTab = false;
        if (selectedTab.length === 0 || tabs.findIndex(tab => tab === selectedTab) === -1) {
            selectedTab = 'fixtures';
        }

        switch (selectedTab) {
            case 'results':
                selectedResultsTab = true;
                break;
            case 'standings':
                selectedStandingsTab = true;
                break;
        }

        this.state = {
            selectedTab,
            selectedResultsTab,
            selectedStandingsTab
        };
    }

    componentDidMount() {
        this.props.getMajorLeagues();
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
        let { selectedResultsTab, selectedStandingsTab } = this.state;

        switch (key) {
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

        this.setState({ selectedTab: key, selectedResultsTab, selectedStandingsTab });

        const params = window.getMatchByStageParams;
        const url = Site.resolveClientUrl(`lich-thi-dau/bong-da/chi-tiet-giai-dau/${params.date}/${params.leagueId}/${params.seasonId}/${key}`);
        this.props.navigate(url);
    };

    render() {
        const { selectedTab, selectedResultsTab, selectedStandingsTab } = this.state;
        const { majorLeagues, league, stages, preMatches, postMatches } = this.props;

        return (
            <div className="container livescore-container">
                <SignalR getMatches={this.getMatches} />
                <div className="livescore-soccer">
                    <div className="livescore-soccer-main">
                        {
                            league !== null && <Breadscrum currentPage = {CommonFunctions.getLeagueName(league)} />
                        }
                        {
                            league === null && <SkeletonLeague />
                        }
                        {
                            league !== null && <League league={league} />
                        }
                        <Tabs activeKey={selectedTab} id="livescore-tab-league-detail"
                            className="live-score-nav"
                            onSelect={this.handleSelectTab}
                        >
                            <Tab eventKey="fixtures" title="Lịch thi đấu">
                                {
                                    preMatches === null && <SkeletonGrid />
                                }
                                {
                                    preMatches !== null && <MatchGrid sortAsc stages={stages} matches={preMatches} />
                                }
                            </Tab>
                            <Tab eventKey="results" title="Kết quả">
                                {
                                    selectedResultsTab && postMatches === null && <SkeletonGrid />
                                }
                                {
                                    selectedResultsTab && postMatches !== null &&
                                    <MatchGrid sortAsc={false} stages={stages} matches={postMatches} />
                                }
                            </Tab>
                            <Tab eventKey="standings" title="BXH" disabled={!league || !league.hasSeasonStandings}>
                                {
                                    selectedStandingsTab &&
                                    <SeasonStandings />
                                }
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

const AppContainerWithNavigation = withNavigation(AppContainer);

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { majorLeagues, league, stages, preMatches, postMatches } = state;
    return { majorLeagues, league, stages, preMatches, postMatches };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainerWithNavigation);