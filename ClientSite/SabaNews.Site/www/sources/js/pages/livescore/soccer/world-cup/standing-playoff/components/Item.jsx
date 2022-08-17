import React, { PureComponent } from 'react';
import CommonFunctions from '@jsroot/common/common-functions';
import TeamIcon from '@jsroot/components/Icon/TeamIcon';
import { MatchResult } from '@jsroot/common/constants';
import DateTimeService from '@jsroot/common/dateTimeService';

export default class Item extends PureComponent {
    constructor(props) {
        super(props);

        this.state = {
            isHighLight: false,
            isShowPopup: false
        };
    }
    getScore = (match, isHomeTeam) => {
        return (
            <>
                {
                    match.matchId && match.status === MatchStatus.Finished &&
                    <div className="result ">{CommonFunctions.getScore(match, isHomeTeam)}</div>
                }
                {
                    match.secondLeg && match.status === MatchStatus.Finished &&
                    <div className="result ">{CommonFunctions.getScore(match.secondLeg, !isHomeTeam)}</div>
                }
            </>
        );
    };

    handleClick = (event, match) => {
        CommonFunctions.navigateToMatchDetail(event, match);
    };

    handleShowPopup = () => {
        this.setState({isHighLight: !this.state.isHighLight});
    };

    getTeamName = team =>  team ? CommonFunctions.getTeamName(team) : '';


    renderMatch = match => {
        const time = DateTimeService.convertFromUTC(match.startTime);
        const homeTeamScore = match.homeTeamExtraScore ? match.homeTeamExtraScore : match.homeTeamTotalScore;
        const awayTeamScore = match.awayTeamExtraScore ? match.awayTeamExtraScore : match.awayTeamTotalScore;
        const matchResult = CommonFunctions.getMatchResult(match.homeTeam.teamId, match);
        return (
            <div className="series" onClick={event => this.handleClick(event, match)}>
                <div className="series__names">
                    <ul>
                        <li className={matchResult === MatchResult.Win ? 'win': ''}>{CommonFunctions.getTeamName(match.homeTeam)}</li>
                        <li className={matchResult === MatchResult.Lose ? 'win': ''}>{CommonFunctions.getTeamName(match.awayTeam)}</li>
                    </ul>
                </div>
                <div className="result ">
                    <ul>
                        <li className="win">{match.status === MatchStatus.Finished ? homeTeamScore : '-'}</li>
                        <li>{match.status === MatchStatus.Finished ? awayTeamScore: '-'}</li>
                    </ul>
                </div>
                <div className="series__time">{time.format('DD.MM')}</div>
            </div>
        );
    };

    renderPopup = match => {
        return (
            <div className="bracket__series">
                {this.renderMatch(match)}
                {
                    match.secondLeg &&
                    this.renderMatch(match.secondLeg)
                }
            </div>
        );
    };

    renderBody = match => {
        const { isHighLight } = this.state;
        let matchResult = MatchResult.Draw;
        if(match.matchId) {
            const checkMatch = match.secondLeg ? match.secondLeg : match;
            matchResult = CommonFunctions.getMatchResult(match.homeTeam.teamId, checkMatch);
        }
        return (
            <div className={`bracket ${isHighLight && match.matchId ? 'bracket--active' : ''} ${match.matchId ? '' : 'bracket--eventless'}`} onClick={this.handleShowPopup}
                title={match.matchId ? 'Nhấp để xem danh sách trận đấu' : ''}>
                {
                    match.homeTeam &&
                    <TeamIcon className="bracket__image bracket__image--home" src={match.homeTeam.icon} />
                }
                <div className={`bracket__participant bracket__participant--home ${matchResult === MatchResult.Win ? 'win' : ''}`}>
                    <span className="bracket__name ">{this.getTeamName(match.homeTeam)}</span>
                </div>
                <div className="bracket__result bracket__result--home">
                    {this.getScore(match, true)}
                </div>
                {
                    match.awayTeam &&
                    <TeamIcon className="bracket__image bracket__image--away" src={match.awayTeam.icon} />
                }
                <div className={`bracket__participant bracket__participant--away ${matchResult === MatchResult.Lose ? 'win' : ''}`}>
                    <span className="bracket__name bracket__name--advancing">{this.getTeamName(match.awayTeam)}</span>
                </div>
                <div className="bracket__result bracket__result--away">
                    {this.getScore(match, false)}
                </div>
            </div>
        );
    };
    render() {
        const { match, positionClass, index} = this.props;
        const { isHighLight } = this.state;
        return (
            <div className={`draw__bracket ${positionClass} ${match.isDraw === false ? 'no-border' : ''}`} style={{zIndex: -index}}>
                {this.renderBody(match)}
                {
                    match.matchId && isHighLight &&
                    this.renderPopup(match)
                }
            </div>
        );
    }
}