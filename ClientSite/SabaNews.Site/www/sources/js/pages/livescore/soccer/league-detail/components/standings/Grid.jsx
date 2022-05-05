import React, { PureComponent } from 'react';
import TeamIcon from '@jsroot/components/Icon/TeamIcon';
import CommonFunctions from '@jsroot/common/common-functions';

export default class Grid extends PureComponent {
    getMatchResult = (teamId, match) => {
        const isHomeTeam = match.homeTeamId === teamId;
        const homeFieldName = isHomeTeam ? 'homeTeam' : 'awayTeam';
        const awayFieldName = isHomeTeam ? 'awayTeam' : 'homeTeam';
        let result;
        const win = <div className="wld win">T</div>;
        const loss = <div className="wld loss">B</div>;
        const draw = <div className="wld draw">H</div>;
        if (match[`${homeFieldName}PenaltyScore`]) {
            if (match[`${homeFieldName}PenaltyScore`] > match[`${awayFieldName}PenaltyScore`]) {
                result = win;
            } else {
                result = loss;
            }
        } else if (match[`${homeFieldName}AggregateScore`]) {
            if (match[`${homeFieldName}AggregateScore`] > match[`${awayFieldName}AggregateScore`]) {
                result = win;
            } else {
                result = loss;
            }
        } else if (match[`${homeFieldName}ExtraScore`]) {
            if (match[`${homeFieldName}ExtraScore`] > match[`${awayFieldName}ExtraScore`]) {
                result = win;
            } else if (match[`${homeFieldName}ExtraScore`] < match[`${awayFieldName}ExtraScore`]) {
                result = loss;
            } else {
                result = draw;
            }
        } else {
            if (match[`${homeFieldName}TotalScore`] > match[`${awayFieldName}TotalScore`]) {
                result = win;
            } else if (match[`${homeFieldName}TotalScore`] < match[`${awayFieldName}TotalScore`]) {
                result = loss;
            } else {
                result = draw;
            }
        }

        return result;
    };

    getGroupName = groupName => `Bảng ${groupName.charAt(0)}`;

    render() {
        const { standings, isMobile } = this.props;
        const totalItems = standings.length;

        return (
            <>
                {
                    standings.map((group, index) => {
                        return (
                            <div key={index} className="table-custom">
                                <div className="table-custom-header ">
                                    <div className="table-custom-cell cell-rank">#</div>
                                    <div className="table-custom-cell cell-participant">
                                        {totalItems > 1 ? this.getGroupName(group.key) : 'Đội'}
                                    </div>
                                    <div className="table-custom-cell cell-rank">Trận</div>
                                    {!isMobile && <div className="table-custom-cell cell-rank">Thắng</div>}
                                    {!isMobile && <div className="table-custom-cell cell-rank">Hòa</div>}
                                    {!isMobile && <div className="table-custom-cell cell-rank">Bại</div>}
                                    {!isMobile && <div className="table-custom-cell cell-rank">Bàn thắng</div>}
                                    {!isMobile && <div className="table-custom-cell cell-rank">Bàn bại</div>}
                                    <div className="table-custom-cell cell-rank">+/-</div>
                                    <div className="table-custom-cell cell-rank">Điểm</div>
                                    {!isMobile && <div className="table-custom-cell cell-rank cell-form">Phong độ</div>}
                                </div>
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
                                            )

                                        })
                                    }
                                </div>
                            </div>
                        );
                    })
                }
            </>
        );
    }
}