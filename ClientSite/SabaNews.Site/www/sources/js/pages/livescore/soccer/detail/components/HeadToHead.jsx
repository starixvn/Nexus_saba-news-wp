import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import { Tabs, Tab } from 'react-bootstrap';

export class HeadToHead extends Component {
    constructor(props) {
        super(props);
    }

    renderBody = () =>  {
        return (
            <div className="hth-section">
                <div className="f-row">
                    <div className="football-match">
                        <div className="columns-time w-col1">
                            <span className="time">06/03/2022</span>
                        </div>
                        <div className="columns-match w-col2">
                            <div class="row-teams">
                                <div class="columns-club">
                                    <a class="name-club club1" href="/clubs/man-city-1471.html" title="Man City">
                                        Man City
                                        <img class="logo-club" alt="Man City" src="https://static.bongda24h.vn/Medias/icon/2017/6/26/manchester-city.jpg" />
                                    </a>
                                </div>
                                <div class="columns-number">
                                    <p>
                                        <span class="soccer-scores">1 - 0</span>
                                    </p>
                                </div>
                                <div class="columns-club">
                                    <a class="name-club club2" href="/clubs/atletico-1390.html" title="Atletico">
                                        <img class="logo-club" alt="Atletico" src="https://static.bongda24h.vn/Medias/icon/2020/7/23/AleticoMadrid.png" />
                                        Atletico
                                    </a>
                                </div>
                            </div>
                            <div class="row_match_info"><span class="item_ktv">H1: 0-0</span></div>
                        </div>
                        <div class="columns-other w_col1">
                            <a rel="nofollow" class="btn-f-more more-right" href="/truc-tiep-ket-qua/man-city-vs-atletico-105945.html">></a>
                        </div>
                    </div>
                </div>
                <div className="f-row">
                    <div className="football-match">
                        <div className="columns-time w-col1">
                            <span className="time">06/03/2022</span>
                        </div>
                        <div className="columns-match w-col2">
                            <div class="row-teams">
                                <div class="columns-club">
                                    <a class="name-club club1" href="/clubs/man-city-1471.html" title="Man City">
                                        Man City
                                        <img class="logo-club" alt="Man City" src="https://static.bongda24h.vn/Medias/icon/2017/6/26/manchester-city.jpg" />
                                    </a>
                                </div>
                                <div class="columns-number">
                                    <p>
                                        <span class="soccer-scores">1 - 0</span>
                                    </p>
                                </div>
                                <div class="columns-club">
                                    <a class="name-club club2" href="/clubs/atletico-1390.html" title="Atletico">
                                        <img class="logo-club" alt="Atletico" src="https://static.bongda24h.vn/Medias/icon/2020/7/23/AleticoMadrid.png" />
                                        Atletico
                                    </a>
                                </div>
                            </div>
                            <div class="row_match_info"><span class="item_ktv">H1: 0-0</span></div>
                        </div>
                        <div class="columns-other w_col1">
                            <a rel="nofollow" class="btn-f-more more-right" href="/truc-tiep-ket-qua/man-city-vs-atletico-105945.html">></a>
                        </div>
                    </div>
                </div>
                <div className="f-row">
                    <div className="football-match">
                        <div className="columns-time w-col1">
                            <span className="time">06/03/2022</span>
                        </div>
                        <div className="columns-match w-col2">
                            <div class="row-teams">
                                <div class="columns-club">
                                    <a class="name-club club1" href="/clubs/man-city-1471.html" title="Man City">
                                        Man City
                                        <img class="logo-club" alt="Man City" src="https://static.bongda24h.vn/Medias/icon/2017/6/26/manchester-city.jpg" />
                                    </a>
                                </div>
                                <div class="columns-number">
                                    <p>
                                        <span class="soccer-scores">1 - 0</span>
                                    </p>
                                </div>
                                <div class="columns-club">
                                    <a class="name-club club2" href="/clubs/atletico-1390.html" title="Atletico">
                                        <img class="logo-club" alt="Atletico" src="https://static.bongda24h.vn/Medias/icon/2020/7/23/AleticoMadrid.png" />
                                        Atletico
                                    </a>
                                </div>
                            </div>
                            <div class="row_match_info"><span class="item_ktv">H1: 0-0</span></div>
                        </div>
                        <div class="columns-other w_col1">
                            <a rel="nofollow" class="btn-f-more more-right" href="/truc-tiep-ket-qua/man-city-vs-atletico-105945.html">></a>
                        </div>
                    </div>
                </div>
            </div>
        )
        
    }

    render() {
        return (
            <Tabs  id="head-to-head-tabs">
                <Tab eventKey="Home" title="Home" >
                   {this.renderBody()}
                </Tab>
                <Tab eventKey="Chung" title="Chung">
                    {this.renderBody()}
                </Tab>
                <Tab eventKey="Away" title="Away">
                    {this.renderBody()}
                </Tab>
            </Tabs>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { soccerMatch } = state;
    return {
        soccerMatch,
    };
};

export default connect(mapStateToProps, mapDispatchToProps)(HeadToHead);