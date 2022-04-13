import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { Tabs, Tab } from 'react-bootstrap';
import { ActionCreators } from '../actions';
import FavoriteLeagues from '@jsroot/components/FavoriteLeagues';
import FavoriteMatches from '@jsroot/components/FavoriteMatches';
import SignalR from './SignalR';
import League from './League';
import DateTimeService from '@jsroot/common/dateTimeService';

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

    render() {
        const { majorLeagues, league } = this.props;
        return (
            <div className="container livescore-container">
                <SignalR />
                <div className="livescore-soccer">
                    <div className="livescore-soccer-main">
                        {
                            league &&
                            <League league={league} />
                        }
                        <Tabs defaultActiveKey="schedule" id="livescore-tab-league-detail">
                            <Tab eventKey="schedule" title="Lịch thi đấu">
                            </Tab>
                            <Tab eventKey="result" title="Kết quả">
                            </Tab>
                            <Tab eventKey="stadings" title="BXH">
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
    const { majorLeagues, league } = state;
    return { majorLeagues, league };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);