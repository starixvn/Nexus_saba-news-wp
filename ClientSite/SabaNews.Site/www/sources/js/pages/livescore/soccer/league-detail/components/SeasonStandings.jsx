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
        const helper = {};
        return seasonStandings.reduce((previousItem, currentItem) => {
            const key = currentItem.groupName;
            if (!helper[key]) {
                helper[key] = {
                    key: key,
                    childs: [{ ...currentItem }]
                };
                previousItem.push(helper[key]);
            } else {
                helper[key].childs.push(currentItem);
            }

            return previousItem;
        }, []);
    };

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
                            <table className="table table-bordered" key={index}>
                                <thead>
                                    <tr>
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
                                                        <div style={{ backgroundColor: promotion ? promotion.Color : '' }}>
                                                            {item.position}
                                                        </div>
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
                                                    <td></td>
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