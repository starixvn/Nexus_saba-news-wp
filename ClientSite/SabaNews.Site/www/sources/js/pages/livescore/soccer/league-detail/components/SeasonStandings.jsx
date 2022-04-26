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
        const win = <div className="wld win">W</div>;
        const loss = <div className="wld loss">L</div>;
        const draw = <div className="wld draw">D</div>;
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
                            <table className="table table-bordered table-custom" key={index}>
                                <thead>
                                    <tr>
                                        <th />
                                        <th>{totalItems > 1 ? group.key : 'Thứ hạng'}</th>
                                        <th>Trận</th>
                                        <th>Thắng</th>
                                        <th>Hòa</th>
                                        <th>Bại</th>
                                        <th>Bàn thắng</th>
                                        <th>Bàn bại</th>
                                        <th>+/-</th>
                                        <th>Điểm</th>
                                        <th>Phong độ</th>
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
                                                                item.matches.map(match =>
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