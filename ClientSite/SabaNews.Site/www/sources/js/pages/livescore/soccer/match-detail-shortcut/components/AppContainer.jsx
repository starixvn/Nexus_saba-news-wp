import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';

export class AppContainer extends Component {
    constructor(props) {
        super(props);
    }


    render() {
        return (
            <div className="duel-participant">
                <div className="league-and-time">
                    <ul className="league">
                        <li className="league-logo">
                            <img className="duel-participant-home-wrapper-logo" src="https://sports-cdn.huainan710.com/uploads/teamIcon/1747/e2B8m00jcdO.png" />
                        </li>
                        <li className="league-name">AFCON - Vòng 36</li>
                    </ul>
                    <p className="time">
                        <span className="days">Thứ bảy, </span> 6/5/2023, 20:00
                    </p>
                </div>
               <div className="flex-league first-league">
                    <div className="column column-left home-team">
                        <ul className="soccer-team-status">
                            <li>PEN</li>
                        </ul>
                    </div>
                    <div className={`column column-center live`}>
                        <div className="soccer-match-stage">
                            <span className="">90'</span>
                        </div>
                    </div>
                    <div className="column column-right away-team">
                        <ul className="soccer-team-status">
                            <li>PEN</li>
                        </ul>
                    </div>
                </div>

                <div className="flex-league center-league">
                    <div className="column column-left home-team">
                        <ul className="team">
                            <li className="team-name">Croatia</li>
                            <li className="team-logo">
                                <img className="duel-participant-home-wrapper-logo" src="https://sports-cdn.huainan710.com/uploads/teamIcon/1632/cw0zg0O00A0.png" />
                            </li>
                        </ul>
                    </div>
                    <div className="column column-center result-league">
                        <div className="detail-score-wrapper live">
                            <span>0</span>
                            <span className="detail-score-divider"> - </span>
                            <span>0</span>
                        </div>
                    </div>
                    <div className="column column-right away-team">
                        <ul className="team">
                            <li className="team-logo">
                                <img className="duel-participant-home-wrapper-logo" src="https://sports-cdn.huainan710.com/uploads/teamIcon/1632/cw0zg0O00A0.png" />
                            </li>
                            <li className="team-name">Croatia</li>
                        </ul>
                    </div>
                </div>


                <div className="flex-league">
                    <div className="column column-left home-team sticky-position">
                        <div className="duel-participant-number">
                            <div className="participant-detail">
                                <span className="card-league yellow-card">1</span>
                            </div>
                        </div>
                    </div>
                    <ul className="column column-center detail-score-status">
                        <li>H1: 0 - 0</li>
                        <li>PEN: 4 - 5</li>
                    </ul>
                    <div className="column column-right away-team sticky-position">
                        <div className="duel-participant-number">
                            <ul className="participant-detail-group name-player-goal">
                                <li>
                                    <i className="icon-livescore icon-ball"></i>
                                    <span className="name-player">(35') - A. Tshibola</span>
                                </li>
                            </ul>
                            <div className="participant-detail">
                                <span className="card-league yellow-card">3</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="view-detail">
                    <span>
                        Chi tiết trận đấu
                        <i className="icon-chevron-right"/>
                    </span>
                </div>
            </div>
        );
    }

}


const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { match } = state;
    return { match };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);