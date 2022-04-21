import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { Tabs, Tab } from 'react-bootstrap';
import moment from 'moment';
import { ActionCreators } from '../actions';
import FavoriteLeagues from '@jsroot/components/FavoriteLeagues';
import FavoriteMatches from '@jsroot/components/FavoriteMatches';
import SignalR from './SignalR';
import League from './League';
import MatchGrid from './MatchGrid';
import SeasonStandings from './SeasonStandings';
import DateTimeService from '@jsroot/common/dateTimeService';
moment.updateLocale('vi', {
    weekdays: ['Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy']
});
moment.locale('vi');

export class AppContainer extends Component {
    constructor(props) {
        super(props);

        this.state = {
            selectedTab: 'schedule',
            selectedResultTab: false,
            selectedStadingsTab: false
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
        let { selectedResultTab, selectedStadingsTab } = this.state;

        switch (key) {
            case 'result':
                if (!selectedResultTab) {
                    selectedResultTab = true;
                }
                break;
            case 'stadings':
                if (!selectedStadingsTab) {
                    selectedStadingsTab = true;
                }
                break;
        }

        this.setState({ selectedTab: key, selectedResultTab, selectedStadingsTab });
    };

    render() {
        const { selectedTab, selectedResultTab, selectedStadingsTab } = this.state;
        const { majorLeagues, league, stages, preMatches, postMatches } = this.props;

        return (
            <div className="container livescore-container">
                <SignalR getMatches={this.getMatches} />
                <div className="livescore-soccer">
                    <div className="livescore-soccer-main">
                        {
                            league &&
                            <League league={league} />
                        }
                        <Tabs activeKey={selectedTab} id="livescore-tab-league-detail"
                            className="live-score-nav"
                            onSelect={this.handleSelectTab}
                        >
                            <Tab eventKey="schedule" title="Lịch thi đấu">
                                {preMatches && <MatchGrid sortAsc stages={stages} matches={preMatches} />}
                            </Tab>
                            <Tab eventKey="result" title="Kết quả">
                                {
                                    selectedResultTab && postMatches &&
                                    <MatchGrid sortAsc={false} stages={stages} matches={postMatches} />
                                }
                            </Tab>
                            <Tab eventKey="stadings" title="BXH" disabled={!league || !league.hasSeasonStandings}>
                                {
                                    selectedStadingsTab &&
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

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { majorLeagues, league, stages, preMatches, postMatches } = state;
    return { majorLeagues, league, stages, preMatches, postMatches };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);