import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import { Tabs, Tab } from 'react-bootstrap';
import DateTimeService from '@jsroot/common/dateTimeService';

export class HeadToHead extends Component {
    constructor(props) {
        super(props);
    }

    renderBody = matchs =>  {
        return (
            <div className="hth-section">
                 {
                    matchs.map((match, key) => {
                        let homeWinclass = "";
                        let awayWinClass = "";
                        if(match.homeTotalScore > match.awayTotalScore) {
                            homeWinclass = "win";
                        }
                        if(match.homeTotalScore < match.awayTotalScore) {
                            awayWinClass = "win";
                        }
                        const time = DateTimeService.convertFromUTC(match.eventDate);
                        return (
                            <div className="f-row" key={key}>
                                <div className="football-match">
                                    <div className="columns-time w-col1">
                                        <span className="time">{time.format("DD/MM/YYYY")}</span>
                                    </div>
                                    <div className="columns-match w-col2">
                                        <div className="row-teams">
                                            <div className="columns-club">
                                                <div className={`name-club club1 ${homeWinclass}`} >
                                                    {match.homeTeamFullNameEN}
                                                    <img className="logo-club" alt={match.homeTeamFullNameEN} src={match.homeTeamIcon} />
                                                </div>
                                            </div>
                                            <div className="columns-number">
                                                <p>
                                                    <span className="soccer-scores">{match.homeTotalScore} - {match.awayTotalScore}</span>
                                                </p>
                                            </div>
                                            <div className="columns-club">
                                                <div className={`name-club club2 ${awayWinClass}`} >
                                                    <img className="logo-club" alt={match.awayTeamFullNameEN} src={match.awayTeamIcon} />
                                                    {match.awayTeamFullNameEN}
                                                </div>
                                            </div>
                                        </div>
                                        <div className="row_match_info"><span className="item_ktv">H1: {match.homeHalfScore}-{match.awayHalfScore}</span></div>
                                    </div>
                                    <div className="columns-other w-col1">
                                        <a rel="nofollow" className="btn-f-more more-right" href="/truc-tiep-ket-qua/man-city-vs-atletico-105945.html">{'>'}</a>
                                    </div>
                                </div>
                            </div>
                        )
                    })
                }
                <div className="f-row">
                    <div className="football-match">
                        <div className="columns-time w-col1">
                            <span className="time">06/03/2022</span>
                        </div>
                        <div className="columns-match w-col2">
                            <div className="row-teams">
                                <div className="columns-club">
                                    <div className="name-club club1" >
                                        Man City
                                        <img className="logo-club" alt="Man City" src="https://static.bongda24h.vn/Medias/icon/2017/6/26/manchester-city.jpg" />
                                    </div>
                                </div>
                                <div className="columns-number">
                                    <p>
                                        <span className="soccer-scores">1 - 0</span>
                                    </p>
                                </div>
                                <div className="columns-club">
                                    <div className="name-club club2">
                                        <img className="logo-club" alt="Atletico" src="https://static.bongda24h.vn/Medias/icon/2020/7/23/AleticoMadrid.png" />
                                        Atletico
                                    </div>
                                </div>
                            </div>
                            <div className="row_match_info"><span className="item_ktv">H1: 0-0</span></div>
                        </div>
                        <div className="columns-other w-col1">
                            <a rel="nofollow" className="btn-f-more more-right" href="/truc-tiep-ket-qua/man-city-vs-atletico-105945.html">{'>'}</a>
                        </div>
                    </div>
                </div>
            </div>
        )
    }

    render() {
        const {home, away, common} = this.props.soccerMatch.headToHead;
        const generalInfo = this.props.soccerMatch.generalInfo;
        return (
            <Tabs  id="head-to-head-tabs" className="head-to-head">
                <Tab eventKey="Home" title={generalInfo.homeTeam.teamFullNameEN} >
                   {this.renderBody(home)}
                </Tab>
                <Tab eventKey="Chung" title="Chung">
                    {this.renderBody(away)}
                </Tab>
                <Tab eventKey="Away" title={generalInfo.awayTeam.teamFullNameEN}>
                    {this.renderBody(common)}
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