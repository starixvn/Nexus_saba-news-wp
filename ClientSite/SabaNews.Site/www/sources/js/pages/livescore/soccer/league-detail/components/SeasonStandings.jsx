import React, { PureComponent } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';

export class SeasonStandings extends PureComponent {
    componentDidMount() {
        this.props.getSeasonStandings({
            leagueId: window.getMatchByStageParams.leagueId,
            seasonId: window.getMatchByStageParams.seasonId
        });
    }

    groupSeasonStandings = seasonStandings => {
        const totalItems = seasonStandings.length;
        const standings = [];
        for (let i = 0; i < totalItems; i++) {
            const temp = seasonStandings[i];
            const key = temp.groupName;
            const item = {
                key,
                childs: [temp]
            };
            if (standings.length === 0) {
                standings.push(item);
            } else {
                const index = standings.findIndex(x => x.key === key);
                if (index === - 1) {
                    standings.push(item);
                } else {
                    standings[index].childs.push(temp);
                }
            }
        }

        return standings;
    };

    getMatchResult = (teamId, match) => {
        const isHomeTeam = match.homeTeamId === teamId;
        const homeFieldName = isHomeTeam ? 'homeTeam' : 'awayTeam';
        const awayFieldName = isHomeTeam ? 'awayTeam' : 'homeTeam';
        let result = <div />;
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
    }

    getPromotionStandings = seasonStandings => {
        const totalItems = seasonStandings.length;
        const promotions = [];
        for (let i = 0; i < totalItems; i++) {
            const promotion = seasonStandings[i].promotion ? JSON.parse(seasonStandings[i].promotion) : null;
            if (promotion && promotions.findIndex(item => item.Id === promotion.Id) === -1) {
                promotions.push(promotion);
            }
        }

        return promotions;
    };

    render() {
        const seasonStandings = this.groupSeasonStandings(this.props.seasonStandings);
        const promotions = this.getPromotionStandings(this.props.seasonStandings);
        const totalItems = seasonStandings.length;

        return (
            <div className="season-standings">
                {
                    seasonStandings.map((group, index) => {
                        return (
                            <>
                                <div className="table-custom">
                                    <div className="table-custom-header ">
                                        <div className="table-custom-cell cell-rank">#</div>
                                        <div className="table-custom-cell cell-participant">{totalItems > 1 ? group.key : 'Đội'}</div>
                                        <div className="table-custom-cell cell-rank">Trận</div>
                                        <div className="table-custom-cell cell-rank">Thắng</div>
                                        <div className="table-custom-cell cell-rank">Hòa</div>
                                        <div className="table-custom-cell cell-rank">Bại</div>
                                        <div className="table-custom-cell cell-rank">Bàn thắng</div>
                                        <div className="table-custom-cell cell-rank">Bàn bại</div>
                                        <div className="table-custom-cell cell-rank">+/-</div>
                                        <div className="table-custom-cell cell-rank">Điểm</div>
                                        <div className="table-custom-cell cell-rank cell-form">Phong độ</div>
                                    </div>
                                    <div className="table-custom-body">
                                        <div className="table-custom-row">
                                            <div className="table-custom-cell cell-rank">#</div>
                                            <div className="table-custom-cell cell-participant">{totalItems > 1 ? group.key : 'Đội'}</div>
                                            <div className="table-custom-cell cell-rank">Trận</div>
                                            <div className="table-custom-cell cell-rank">Thắng</div>
                                            <div className="table-custom-cell cell-rank">Hòa</div>
                                            <div className="table-custom-cell cell-rank">Bại</div>
                                            <div className="table-custom-cell cell-rank">Bàn thắng</div>
                                            <div className="table-custom-cell cell-rank">Bàn bại</div>
                                            <div className="table-custom-cell cell-rank">+/-</div>
                                            <div className="table-custom-cell cell-rank">Điểm</div>
                                            <div className="table-custom-cell cell-rank cell-form">Phong độ</div>
                                        </div>
                                    </div>
                                </div>




                                <table className="table table-bordered table-custom" key={index}>
                                    <thead>
                                        <tr>
                                            <th style={{ width: "4%" }}>#</th>
                                            <th style={{ width: "26%" }}>{totalItems > 1 ? group.key : 'Đội'}</th>
                                            <th style={{ width: "5%" }}>Trận</th>
                                            <th style={{ width: "5%" }}>Thắng</th>
                                            <th style={{ width: "5%" }}>Hòa</th>
                                            <th style={{ width: "5%" }}>Bại</th>
                                            <th style={{ width: "5%" }}>Bàn thắng</th>
                                            <th style={{ width: "5%" }}>Bàn bại</th>
                                            <th style={{ width: "5%" }}>+/-</th>
                                            <th style={{ width: "5%" }}>Điểm</th>
                                            <th style={{ width: "30%" }}>Phong độ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {
                                            group.childs.map(item => {
                                                const promotion = item.promotion ? JSON.parse(item.promotion) : null;
                                                return (
                                                    <tr key={item.teamId}>
                                                        <td>
                                                            <div className="position" style={{ backgroundColor: promotion ? promotion.Color : '' }}>
                                                                {item.position}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div className="soccer-team">
                                                                <img className="soccer-team-logo" loading="lazy" src={item.icon} />
                                                                <div className="soccer-team-name">{item.teamNameEN ? item.teamNameEN : item.teamFullNameEN}</div>
                                                            </div>
                                                        </td>
                                                        <td>{item.total}</td>
                                                        <td>{item.won}</td>
                                                        <td>{item.draw}</td>
                                                        <td>{item.loss}</td>
                                                        <td>{item.goals}</td>
                                                        <td>{item.goalsAgainst}</td>
                                                        <td>{item.goalDiff}</td>
                                                        <td>{item.points}</td>
                                                        <td>
                                                            <div className="wld-wrapper">
                                                                {
                                                                    item.matches.slice(0, 5).map(match =>
                                                                        <div key={match.matchId}>{this.getMatchResult(item.teamId, match)}</div>)
                                                                }
                                                            </div>
                                                        </td>
                                                    </tr>
                                                );
                                            })
                                        }
                                    </tbody>
                                </table>
                            </>
                        );
                    })
                }
                {
                    promotions.length > 0 &&
                    <div className="promotion">
                        {
                            promotions.map(item => {
                                return <div key={item.Id} style={{ backgroundColor: item.Color }}>{item.NameEN}</div>
                            })
                        }
                    </div>
                }
            </div>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { seasonStandings } = state;
    return { seasonStandings };
};

export default connect(mapStateToProps, mapDispatchToProps)(SeasonStandings);