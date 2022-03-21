import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { Tabs, Tab } from 'react-bootstrap';
import SignalR from './SignalR';
import MatchSummary from './MatchSummary';
import Lineups from './Lineups';
import Statistics from './Statistics';
import { ActionCreators } from '../actions';
import Site from '@jsroot/common/site';

export class AppContainer extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <>
                <SignalR />
                <div className="livescore-soccer">
                    <div className="tournament-header">La Liga</div>
                    <div className="duel-participant">
                        <div className="duel-participant-start-time">25.02.2022 10:45</div>
                        <div className="duel-participant-home">
                            <div className="participant-image">
                                <a>
                                    <img width="58" height="58" alt="Barcelona" loading="lazy"
                                        src={Site.resolveClientUrl(`css/images/teams/Barcelona.png`)} />
                                </a>
                            </div>
                            <div className="participant-name-wrapper">
                                <div className="participant-name">Barcelona</div>
                            </div>
                        </div>
                        <div className="duel-participant-score">
                            <div className="detail-score-match-info live">
                                <div className="detail-score-wrapper live">
                                    <span>1</span>
                                    <span className="detail-score-divider">-</span>
                                    <span>1</span>
                                </div>
                                <div className="detail-score-status">
                                    <span>Hiệp 2</span>
                                    <span> - 64</span>
                                    <span class="blink">'</span>
                                </div>
                            </div>
                        </div>
                        <div className="duel-participant-away">
                            <div className="participant-image">
                                <a>
                                    <img width="58" height="58" alt="Ath Bilbao" loading="lazy"
                                        src={Site.resolveClientUrl(`css/images/teams/AthBilbao.png`)} />
                                </a>
                            </div>
                            <div className="participant-name-wrapper">
                                <div className="participant-name">Ath Bilbao</div>
                            </div>
                        </div>
                    </div>
                    <Tabs defaultActiveKey="MatchSummary" id="soccer-match-detail-tabs">
                        <Tab eventKey="MatchSummary" title="Tóm tắt Trận đấu">
                            <MatchSummary />
                        </Tab>
                        <Tab eventKey="Statistics" title="Thống kê">
                            <Statistics />
                        </Tab>
                        <Tab eventKey="Lineups" title="Đội hình">
                            <Lineups />
                        </Tab>
                    </Tabs>
                </div>
            </>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

export default connect(null, mapDispatchToProps)(AppContainer);