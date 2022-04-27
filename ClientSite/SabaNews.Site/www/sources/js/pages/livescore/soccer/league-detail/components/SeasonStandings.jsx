import React, { PureComponent } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import { MobileMaxWidth } from '@jsroot/common/constants';

export class SeasonStandings extends PureComponent {
    constructor(props) {
        super(props);

        this.state = {
            isMobile: window.innerWidth <= MobileMaxWidth ? true : false
        };
    }

    componentDidMount() {
        window.addEventListener('resize', this.handleResize);

        this.props.getSeasonStandings({
            leagueId: window.getMatchByStageParams.leagueId,
            seasonId: window.getMatchByStageParams.seasonId
        });
    }

    componentWillUnmount() {
        window.removeEventListener('resize', this.handleResize);
    }

    handleResize = () => {
        const isMobile = this.state.isMobile;
        if (!isMobile && window.innerWidth <= MobileMaxWidth) {
            this.setState({ isMobile: true });
        } else {
            if (isMobile && window.innerWidth > MobileMaxWidth) {
                this.setState({ isMobile: false });
            }
        }
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
        const isMobile = this.state.isMobile;

        return (
            <div className="season-standings">
                {
                    seasonStandings.map((group, index) => {
                        return (
                            <div key={index} className="table-custom">
                                <div className="table-custom-header ">
                                    <div className="table-custom-cell cell-rank">#</div>
                                    <div className="table-custom-cell cell-participant">{totalItems > 1 ? group.key : 'Đội'}</div>
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
                                            const promotion = item.promotion ? JSON.parse(item.promotion) : null;
                                            return (
                                                <div className="table-custom-row" key={item.teamId}>
                                                    <div className="table-custom-cell cell-rank">
                                                        <div className={`position ${promotion ? 'postition-color' : ''}`} style={{ backgroundColor: promotion ? promotion.Color : '' }}>
                                                            {item.position}
                                                        </div>
                                                    </div>
                                                    <div className="table-custom-cell cell-participant">
                                                        <div className="soccer-team">
                                                            <img className="soccer-team-logo" loading="lazy" src={item.icon} />
                                                            <div className="soccer-team-name">{item.teamNameEN ? item.teamNameEN : item.teamFullNameEN}</div>
                                                        </div>
                                                    </div>
                                                    <div className="table-custom-cell cell-rank">
                                                        <span>
                                                            {item.total}
                                                        </span>
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
                                                        <span>
                                                            {item.goalDiff}
                                                        </span>
                                                    </div>
                                                    <div className="table-custom-cell cell-rank">
                                                        <span className="table-bold">
                                                            {item.points}
                                                        </span>
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
                {
                    promotions.length > 0 &&
                    <div className="promotion">
                        {
                            promotions.map(item => {
                                return (
                                    <div key={item.Id} className="promotion-flex">
                                        <div className="promotion-color" style={{ backgroundColor: item.Color }}/>
                                        <div className="promation-name">{item.NameEN}</div>
                                    </div>
                                )
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