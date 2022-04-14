import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import { Tabs, Tab } from 'react-bootstrap';
import DateTimeService from '@jsroot/common/dateTimeService';
import Site from '@jsroot/common/site';
import CommonFunctions from '@jsroot/common/common-functions';

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
                    !teamId &&
                    this.renderStatistic(matchs)
                }
                {
                    matchs.map((match, key) => {
                        let homeWinclass = "";
                        let awayWinClass = "";
                        let matchStatus = 0;
                        const homeTeamScore = match.homeTeamExtraScore ? match.homeTeamExtraScore : match.homeTeamTotalScore;
                        const awayTeamScore = match.awayTeamExtraScore ? match.awayTeamExtraScore : match.awayTeamTotalScore;
                        if(homeTeamScore > awayTeamScore) {
                            homeWinclass = "win";
                            if(match.homeTeam.teamId === teamId) {
                                matchStatus = 1;
                            } else {
                                matchStatus = 2;
                            }
                        }
                        if(homeTeamScore < awayTeamScore) {
                            awayWinClass = "win";
                            if(match.awayTeam.teamId === teamId) {
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
                                                    {match.homeTeam.teamFullNameEN}
                                                    <img className="logo-club" alt={match.homeTeam.teamFullNameEN} src={match.homeTeam.icon} />
                                                </div>
                                            </div>
                                            <div className="columns-number">
                                                <p>
                                                    <span className="soccer-scores">{homeTeamScore} - {awayTeamScore}</span>
                                                </p>
                                            </div>
                                            <div className="columns-club">
                                                <div className={`name-club club2 ${awayWinClass}`} >
                                                    <img className="logo-club" alt={match.awayTeam.teamFullNameEN} src={match.awayTeam.icon} />
                                                    {match.awayTeam.teamFullNameEN}
                                                </div>
                                            </div>
                                        </div>
                                        <div className="row_match_info"><span className="item_ktv">HT: {match.homeTeamHalfScore}-{match.awayTeamHalfScore}</span></div>
                                    </div>
                                    <div className="columns-other w-col3">
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
                                        <span className="more-right">{CommonFunctions.getLeagueName(match.league)}</span>
                                    </div>
                                </div>
                            </div>
                        )
                    })
                }
            </div>
        )
    }

    renderStatistic = matchs => {
        const generalInfo = this.props.match.generalInfo;
        let homeWins = 0;
        let draws = 0;
        let awayWins = 0;
        matchs.map(match => {
            const homeTeamScore = match.homeTeamExtraScore ? match.homeTeamExtraScore : match.homeTeamTotalScore;
            const awayTeamScore = match.awayTeamExtraScore ? match.awayTeamExtraScore : match.awayTeamTotalScore;
            if(match.homeTeam.teamId === generalInfo.homeTeam.teamId && homeTeamScore > awayTeamScore) {
                homeWins++;
            }
            if(match.awayTeam.teamId === generalInfo.homeTeam.teamId && homeTeamScore < awayTeamScore) {
                homeWins++;
            }
            if(match.homeTeam.teamId === generalInfo.awayTeam.teamId && homeTeamScore > awayTeamScore) {
                awayWins++;
            }
            if(match.awayTeam.teamId === generalInfo.awayTeam.teamId && homeTeamScore < awayTeamScore) {
                awayWins++;
            }
            if(homeTeamScore === awayTeamScore) {
                draws++;
            }
        });
        return (
            <div className="h2h-statistic">
                <ul>
                    <li>
                        <p>{homeWins}</p>
                        <p>Thắng</p>
                    </li>
                    <li>
                        <p>{draws}</p>
                        <p>Hòa</p>
                    </li>
                    <li>
                    <p>
                        {awayWins}</p>
                        <p>Thắng</p>
                    </li>
                </ul>
            </div>
        )
    }

    render() {
        const headToHead = this.props.match.headToHead;
        if(!headToHead || (headToHead.home.length === 0 && headToHead.away.length === 0 && headToHead.common.length === 0)) {
            return null;
        }
        const { home, away, common } = this.props.match.headToHead;
        const generalInfo = this.props.match.generalInfo;
        return (
            <Tabs  id="head-to-head-tabs" className="head-to-head" defaultActiveKey="Common">
                <Tab eventKey="Home" title={generalInfo.homeTeam.teamFullNameEN} >
                   {this.renderBody(home, generalInfo.homeTeam.teamId)}
                </Tab>
                <Tab eventKey="Common" title="Chung">
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