import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import { Tabs, Tab } from 'react-bootstrap';
import DateTimeService from '@jsroot/common/dateTimeService';
import Site from '@jsroot/common/site';

export class HeadToHead extends Component {
    constructor(props) {
        super(props);
    }

    renderBody = (matchs, teamId) =>  {
        if(matchs.length === 0) {
            return null;
        }
        return (
            <div className="hth-section">
                 {
                    matchs.map((match, key) => {
                        let homeWinclass = "";
                        let awayWinClass = "";
                        let matchStatus = 0;
                        if(match.homeTotalScore > match.awayTotalScore) {
                            homeWinclass = "win";
                            if(match.homeTeamId === teamId) {
                                matchStatus = 1;
                            } else {
                                matchStatus = 2;
                            }
                        }
                        if(match.homeTotalScore < match.awayTotalScore) {
                            awayWinClass = "win";
                            if(match.awayTeamId === teamId) {
                                matchStatus = 1;
                            } else {
                                matchStatus = 2;
                            }
                        }
                        if(!teamId) {
                            matchStatus = -1;
                        }
                        const time = DateTimeService.convertFromUTC(match.startTime);
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
                                        <div className="row_match_info"><span className="item_ktv">HT: {match.homeHalfScore}-{match.awayHalfScore}</span></div>
                                    </div>
                                    <div className="columns-other w-col1">
                                        <a rel="nofollow" className="btn-f-more more-right" href={Site.resolveClientUrl(`lich-thi-dau/bong-da/chi-tiet/${match.matchId}`)}>{'>'}</a>
                                        {
                                            matchStatus === 0 &&
                                            <span className="bg-color  bgyelow more-right btn-f-more" title="Hòa">D</span>
                                        }
                                        {
                                            matchStatus === 1 &&
                                            <span className="bg-color bggreen more-right btn-f-more" title="Thắng">W</span>
                                        }
                                        {
                                            matchStatus === 2 &&
                                            <span className="bg-color bgred more-right btn-f-more" title="Bại">L</span>
                                        }
                                    </div>
                                </div>
                            </div>
                        )
                    })
                }
            </div>
        )
    }

    render() {
        console.log("headtohead");
        const headToHead = this.props.match.headToHead;
        if(!headToHead || (headToHead.home.length === 0 && headToHead.away.length === 0 && headToHead.common.length === 0)) {
            return null;
        }
        
        const { home, away, common } = this.props.match.headToHead;
        const generalInfo = this.props.match.generalInfo;
        return (
            <Tabs  id="head-to-head-tabs" className="head-to-head">
                <Tab eventKey="Home" title={generalInfo.homeTeam.teamFullNameEN} >
                   {this.renderBody(home, generalInfo.homeTeam.teamId)}
                </Tab>
                <Tab eventKey="Chung" title="Chung">
                    {this.renderBody(common)}
                </Tab>
                <Tab eventKey="Away" title={generalInfo.awayTeam.teamFullNameEN}>
                    {this.renderBody(away, generalInfo.awayTeam.teamId)}
                </Tab>
            </Tabs>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { match, headToHead } = state;
    return {
        match,
        headToHead
    };
};

export default connect(mapStateToProps, mapDispatchToProps)(HeadToHead);