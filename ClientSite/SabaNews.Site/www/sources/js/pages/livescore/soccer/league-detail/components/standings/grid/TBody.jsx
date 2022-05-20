import React, { PureComponent } from 'react';
import TeamIcon from '@jsroot/components/Icon/TeamIcon';
import CommonFunctions from '@jsroot/common/common-functions';

export default class TBody extends PureComponent {
    getResult = (homeScore, awayScore) => {
        const win = <div className="wld win">T</div>;
        const loss = <div className="wld loss">B</div>;
        const draw = <div className="wld draw">H</div>;
        let result;
        if (homeScore > awayScore) {
            result = win;
        } else if (homeScore < awayScore) {
            result = loss;
        } else {
            result = draw;
        }

        return result;
    };

    getMatchResult = (teamId, match) => {
        const isHomeTeam = match.homeTeamId === teamId;
        const homeFieldName = isHomeTeam ? 'homeTeam' : 'awayTeam';
        const awayFieldName = isHomeTeam ? 'awayTeam' : 'homeTeam';
        let result;
        if (match[`${homeFieldName}PenaltyScore`] || match[`${awayFieldName}PenaltyScore`]) {
            result = this.getResult(match[`${homeFieldName}PenaltyScore`], match[`${awayFieldName}PenaltyScore`]);
        } else if (match.isSecondLeg && (match[`${homeFieldName}AggregateScore`] || match[`${awayFieldName}AggregateScore`])) {
            result = this.getResult(match[`${homeFieldName}AggregateScore`], match[`${awayFieldName}AggregateScore`]);
        } else if (match[`${homeFieldName}ExtraScore`] || match[`${awayFieldName}ExtraScore`]) {
            result = this.getResult(match[`${homeFieldName}ExtraScore`], match[`${awayFieldName}ExtraScore`]);
        } else {
            result = this.getResult(match[`${homeFieldName}TotalScore`], match[`${awayFieldName}TotalScore`]);
        }

        return result;
    };

    render() {
        const { group, isMobile } = this.props;

        return (
            <div className="table-custom-body">
                {
                    group.childs.map(item => {
                        return (
                            <div className="table-custom-row" key={item.teamId}>
                                <div className="table-custom-cell cell-rank">
                                    <div className={`position ${item.promotion ? 'postition-color' : ''}`}
                                        style={{ backgroundColor: item.promotion ? item.promotion.Color : '' }}
                                    >
                                        {item.position}
                                    </div>
                                </div>
                                <div className="table-custom-cell cell-participant">
                                    <div className="soccer-team">
                                        <div className="soccer-team-logo"><TeamIcon className="soccer-team-logo" src={item.icon} /></div>
                                        <div className="soccer-team-name">{CommonFunctions.getTeamName(item)}</div>
                                    </div>
                                </div>
                                <div className="table-custom-cell cell-rank">
                                    <span>{item.total}</span>
                                </div>
                                {
                                    !isMobile &&
                                    <div className="table-custom-cell cell-rank">
                                        <span>{item.won}</span>
                                    </div>
                                }
                                {
                                    !isMobile &&
                                    <div className="table-custom-cell cell-rank">
                                        <span>{item.draw}</span>
                                    </div>
                                }
                                {
                                    !isMobile &&
                                    <div className="table-custom-cell cell-rank">
                                        <span>{item.loss}</span>
                                    </div>
                                }
                                {
                                    !isMobile &&
                                    <div className="table-custom-cell cell-rank">
                                        <span>{item.goals}</span>
                                    </div>
                                }
                                {
                                    !isMobile &&
                                    <div className="table-custom-cell cell-rank">
                                        <span>{item.goalsAgainst}</span>
                                    </div>
                                }
                                <div className="table-custom-cell cell-rank">
                                    <span>{item.goalDiff}</span>
                                </div>
                                <div className="table-custom-cell cell-rank">
                                    <span className="table-bold">{item.points}</span>
                                </div>
                                {
                                    !isMobile &&
                                    <div className="table-custom-cell cell-rank cell-form">
                                        <div className="wld-wrapper">
                                            {
                                                item.matches.slice(0, 5).map(match =>
                                                    <div key={match.matchId}>{this.getMatchResult(item.teamId, match)}</div>)
                                            }
                                        </div>
                                    </div>
                                }
                            </div>
                        );
                    })
                }
            </div>
        );
    }
}