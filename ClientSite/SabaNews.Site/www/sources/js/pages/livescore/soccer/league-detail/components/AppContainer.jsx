import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { Tabs, Tab } from 'react-bootstrap';
import { ActionCreators } from '../actions';
import FavoriteLeagues from '@jsroot/components/FavoriteLeagues';
import FavoriteMatches from '@jsroot/components/FavoriteMatches';
import SignalR from './SignalR';
import League from './League';
import MatchGrid from './match-grid';
import DateTimeService from '@jsroot/common/dateTimeService';
import { MatchStatus } from '@jsroot/common/constants';

export class AppContainer extends Component {
    componentDidMount() {
        this.props.getMajorLeagues();
        this.getMatches();
    }

    getMatches = () => {
        const params = {
            ...window.getMatchByStageParams,
            timezone: DateTimeService.getClientTimezone()
        };
        this.props.getMatchesByStage(params);
    };

    getPreMatches = matches => {
        return matches.filter(match => match.status === MatchStatus.NotStarted
            || match.status === MatchStatus.FirstHalf
            || match.status === MatchStatus.SecondHalf
            || match.status === MatchStatus.Intermission
            || match.status === MatchStatus.ExtraTime
            || match.status === MatchStatus.PenaltyShootout);
    };

    getPostMatches = matches => matches.filter(match => match.status === MatchStatus.Finished);

    render() {
        const { majorLeagues, league, matches } = this.props;
        let preMatches = [],
            postMatches = [];
        if (matches && matches.length > 0) {
            preMatches = this.getPreMatches(matches);
            postMatches = this.getPostMatches(matches);
        }

        return (
            <div className="container livescore-container">
                <SignalR />
                <div className="livescore-soccer">
                    <div className="livescore-soccer-main">
                        {
                            league &&
                            <League league={league} />
                        }
                        <Tabs defaultActiveKey="schedule" id="livescore-tab-league-detail" className="live-score-nav">
                            <Tab eventKey="schedule" title="Lịch thi đấu">
                                <MatchGrid matches={preMatches} />
                            </Tab>
                            <Tab eventKey="result" title="Kết quả">
                                <MatchGrid matches={postMatches} />
                            </Tab>
                            <Tab eventKey="stadings" title="BXH" disabled={!league || !league.hasSeasonStandings}>
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
    const { majorLeagues, league, matches } = state;
    return { majorLeagues, league, matches };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);