import React, { PureComponent } from 'react';
import LangHelper from '@jsroot/common/langHelper';
import { MatchStatus } from '@jsroot/common/constants';

export default class Match extends PureComponent {
    getMatchStage = match => {
        switch (match.status) {
            case MatchStatus.NotStarted:
                return <span>{match.schedule}</span>;
                break;

            case MatchStatus.FirstHalf:
            case MatchStatus.SecondHalf:
                return <span>{match.matchTime}</span>;
                break;

            case MatchStatus.Delay:
                return <span>{LangHelper.getResource('Delay')}</span>;
                break;

            case MatchStatus.Postponed:
                return <span>{LangHelper.getResource('Postponed')}</span>;
                break;

            case MatchStatus.TBD:
                return <span>{LangHelper.getResource('TBD')}</span>;
                break;

            default:
                return <span>{LangHelper.getResource('Finish')}</span>;
                break;
        }
    };

    getMatchScores = match => {
        switch (match.status) {
            case MatchStatus.NotStarted:
            case MatchStatus.Delay:
            case MatchStatus.Postponed:
            case MatchStatus.Cancel:
            case MatchStatus.TBD:
                return <span>&nbsp; - &nbsp;</span>;
                break;
            default:
                return <>
                    <div className="font-weight-bold">
                        <span>{match.homeTeamTotalScore}</span>&nbsp; - &nbsp;<span>{match.awayTeamTotalScore}</span>
                    </div>
                    <div>
                        H1: <span>{match.homeTeamHalfScore}</span>&nbsp; - &nbsp;<span>{match.awayTeamHalfScore}</span>
                    </div>
                </>;
                break;
        }
    };

    render() {
        const { match, searchKey } = this.props;
        const reg = new RegExp(`(${searchKey})`, 'gi');
        const displayHomeTeamValue = match.homeTeam.teamFullNameEN.replace(reg, "<span class='text-matched'>$1</span>");
        const displayAwayTeamValue = match.awayTeam.teamFullNameEN.replace(reg, "<span class='text-matched'>$1</span>");

        return (
            <div className={`soccer-match border-bottom ${match.status === MatchStatus.FirstHalf || match.status === MatchStatus.SecondHalf ? 'live' : ''}`}>
                <div className="soccer-match-stage">{this.getMatchStage(match)}</div>


                <ul className="soccer-match">
                    <li></li>
                </ul>

                <div className="soccer-match-participant-home" dangerouslySetInnerHTML={{ __html: displayHomeTeamValue }} />
                <img className="soccer-match-participant-home-logo" loading="lazy"
                    src={match.homeTeam.icon} />
                <div className="soccer-match-participant-away" dangerouslySetInnerHTML={{ __html: displayAwayTeamValue }} />
                <img className="soccer-match-participant-away-logo" loading="lazy"
                    src={match.awayTeam.icon} />



                <div className="soccer-match-scores">{this.getMatchScores(match)}</div>



                <div className="soccer-match-star-icon">
                    <span className="fa fa-star-o" />
                </div>
            </div>
        );
    }
}