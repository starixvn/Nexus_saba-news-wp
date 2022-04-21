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

    getTeamStatus = (isHomeTeam) => {
        const { generalInfo } = this.props.match;
        const homeFieldName = isHomeTeam ? 'homeTeam' : 'awayTeam';
        const awayFieldName = isHomeTeam ? 'awayTeam' : 'homeTeam';

        switch (generalInfo.status) {
            case MatchStatus.Finished:
                if (generalInfo[`${homeFieldName}PenaltyScore`]
                    && generalInfo[`${homeFieldName}PenaltyScore`] > generalInfo[`${awayFieldName}PenaltyScore`]) {
                    return <li>PEN</li>;
                } else if (generalInfo[`${homeFieldName}AggregateScore`]
                    && generalInfo[`${homeFieldName}AggregateScore`] > generalInfo[`${awayFieldName}AggregateScore`]) {
                    return <li>AGG</li>;
                } else {
                    return <Fragment />;
                }
            default:
                return <Fragment />;
        }
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
                        const homeTeamScore = match.homeTeamExtraScore ? match.homeTeamExtraScore : match.homeTeamTotalScore;
                        const awayTeamScore = match.awayTeamExtraScore ? match.awayTeamExtraScore : match.awayTeamTotalScore;
                        let result = this.getTeamResult(teamId, match);
                        if(result === 1) {
                            if(teamId === match.homeTeam.teamId) {
                                homeWinclass = "win";
                            } else if(teamId === match.awayTeam.teamId) {
                                awayWinClass = "win";
                            }
                        }
                        if(result === 2) {
                            if(teamId === match.homeTeam.teamId) {
                                awayWinClass = "win";
                            } else if(teamId === match.awayTeam.teamId) {
                                homeWinclass = "win";
                            }
                        }
                        if(!teamId) {
                            result = -1;
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
                                                    {match.homeTeam.teamNameEN}
                                                    <img className="logo-club" alt={match.homeTeam.teamNameEN} src={match.homeTeam.icon} />
                                                </div>
                                            </div>
                                            <div className="columns-number">
                                                <p>
                                                    <span className="soccer-scores">{homeTeamScore} - {awayTeamScore}</span>
                                                </p>
                                            </div>
                                            <div className="columns-club">
                                                <div className={`name-club club2 ${awayWinClass}`} >
                                                    <img className="logo-club" alt={match.awayTeam.teamNameEN} src={match.awayTeam.icon} />
                                                    {match.awayTeam.teamNameEN}
                                                </div>
                                            </div>
                                        </div>
                                        <div className="row_match_info"><span className="item_ktv">HT: {match.homeTeamHalfScore}-{match.awayTeamHalfScore}</span></div>
                                    </div>
                                    <div className="columns-other w-col3">
                                        <a rel="nofollow" className="btn-f-more more-right" href={Site.resolveClientUrl(`lich-thi-dau/bong-da/chi-tiet/${match.matchId}`)}>{'>'}</a>
                                        {
                                            result === 0 &&
                                            <span className="bg-color  bgyelow more-right btn-f-more" title="Hòa">D</span>
                                        }
                                        {
                                            result === 1 &&
                                            <span className="bg-color bggreen more-right btn-f-more" title="Thắng">W</span>
                                        }
                                        {
                                            result === 2 &&
                                            <span className="bg-color bgred more-right btn-f-more" title="Bại">L</span>
                                        }
                                        <span className="more-right">{CommonFunctions.getLeagueNameWithStage(match.league)}</span>
                                    </div>
                                </div>
                            </div>
                        )
                    })
                }
            </div>
        )
    }

    getTeamResult = (teamId, match) => {
        const isHomeTeam = match.homeTeam.teamId === teamId;
        const homeFieldName = isHomeTeam ? 'homeTeam' : 'awayTeam';
        const awayFieldName = isHomeTeam ? 'awayTeam' : 'homeTeam';
        let status = 0;
        const homeTeamScore = match[`${homeFieldName}ExtraScore`] ? match[`${homeFieldName}ExtraScore`] : match[`${homeFieldName}TotalScore`];
        const awayTeamScore = match[`${awayFieldName}ExtraScore`] ? match[`${awayFieldName}ExtraScore`] : match[`${awayFieldName}TotalScore`];
        if(homeTeamScore > awayTeamScore) {
           status = 1;
        } else if (homeTeamScore < awayTeamScore) {
            status = 2;
        }
        else {
            status = 0;
        }
        if (match[`${homeFieldName}PenaltyScore`]) {
            if(match[`${homeFieldName}PenaltyScore`] > match[`${awayFieldName}PenaltyScore`]) {
                status = 1;
            } else if (match[`${homeFieldName}PenaltyScore`] < match[`${awayFieldName}PenaltyScore`])  {
                status = 2;
            }
        } else if (match[`${homeFieldName}AggregateScore`]) {
            if(match[`${homeFieldName}AggregateScore`] > match[`${awayFieldName}AggregateScore`]) {
                status = 1;
            } else if (match[`${homeFieldName}AggregateScore`] < match[`${awayFieldName}AggregateScore`])  {
                status = 2;
            }
        }
        return status;
    }

    renderStatistic = matchs => {
        const generalInfo = this.props.match.generalInfo;
        let homeWins = 0;
        let draws = 0;
        let awayWins = 0;
        matchs.map(match => {
            const result = this.getTeamResult(generalInfo.homeTeam.teamId, match);
            switch (result) {
                case 0:
                    draws++;
                    break;
                case 1:
                    homeWins++;
                    break;
                case 2:
                    awayWins++;
                    break;
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
            <div id="doi-dau">
                <Tabs  id="head-to-head-tabs" className="head-to-head" defaultActiveKey="Common">
                    <Tab eventKey="Home" title={generalInfo.homeTeam.teamNameEN} >
                    {this.renderBody(home, generalInfo.homeTeam.teamId)}
                    </Tab>
                    <Tab eventKey="Common" title="Chung">
                        {this.renderBody(common)}
                    </Tab>
                    <Tab eventKey="Away" title={generalInfo.awayTeam.teamNameEN}>
                        {this.renderBody(away, generalInfo.awayTeam.teamId)}
                    </Tab>
                </Tabs>
            </div>
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