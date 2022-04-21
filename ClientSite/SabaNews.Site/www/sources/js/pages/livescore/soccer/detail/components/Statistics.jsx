import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import Site from '@jsroot/common/site';

export class Statistics extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        const match = this.props.match;
        const { statistic } = this.props.match;
        if (!statistic || !statistic.homeStatistic || !statistic.awayStatistic || Site.CheckPostMatchStatus(match.generalInfo.status)) {
            return null;
        }
        let statistices  = [];
        if(match !== null) {
            const {homeStatistic, awayStatistic} = this.props.match.statistic;
            statistices = [
                { categoryId: 1, categoryName: 'Kiểm soát bóng', homeValue: homeStatistic.controlRate, awayValue: awayStatistic.controlRate, unit: '%' },
                { categoryId: 3, categoryName: 'Số lần dứt điểm', homeValue: homeStatistic.shoot, awayValue: awayStatistic.shoot, unit: '' },
                { categoryId: 4, categoryName: 'Dứt điểm chính xác', homeValue: homeStatistic.onGoal, awayValue: awayStatistic.onGoal, unit: '' },
                { categoryId: 5, categoryName: 'Phạm lỗi', homeValue: homeStatistic.foul, awayValue: awayStatistic.foul, unit: '' },
                { categoryId: 6, categoryName: 'Thẻ Vàng', homeValue: homeStatistic.yellowCard, awayValue: awayStatistic.yellowCard, unit: '' },
                { categoryId: 7, categoryName: 'Thẻ Đỏ', homeValue: homeStatistic.redCard, awayValue: awayStatistic.redCard, unit: '' },
                { categoryId: 8, categoryName: 'Việt vị', homeValue: homeStatistic.offSide, awayValue: awayStatistic.offSide, unit: '' },
                { categoryId: 9, categoryName: 'Ném biên', homeValue: homeStatistic.outBall, awayValue: awayStatistic.outBall, unit: '' },
                { categoryId: 10, categoryName: 'Phạt góc', homeValue: homeStatistic.cornerKick, awayValue: awayStatistic.cornerKick, unit: '' },
                { categoryId: 11, categoryName: 'Đá phạt', homeValue: homeStatistic.freeKick, awayValue: awayStatistic.freeKick, unit: '' },
                { categoryId: 12, categoryName: 'Phạt đền', homeValue: homeStatistic.penaltyKick, awayValue: awayStatistic.penaltyKick, unit: '' },
                { categoryId: 13, categoryName: 'Tình huống tấn công nguy hiểm', homeValue: homeStatistic.dangerAttack, awayValue: awayStatistic.dangerAttack, unit: '' },
                { categoryId: 14, categoryName: 'Cướp bóng thành công', homeValue: homeStatistic.steals, awayValue: awayStatistic.steals, unit: '' }
            ];
        }
        return (
            <div id="thong-ke">
                <h2 className="heading-tab">Thống kê</h2>
                <div className="statistices">
                    {
                        statistices.map(item => {
                            const homePercentage = (item.homeValue / (item.homeValue + item.awayValue)) * 100;
                            const awayPercentage = (item.awayValue / (item.homeValue + item.awayValue)) * 100;

                            return (
                                <>
                                    {
                                        (item.homeValue !== null && item.awayValue !== null) &&
                                        <div key={item.categoryId} className="statistices-row">
                                        <div className="statistices-category">
                                            <div className="statistices-home-value">{item.homeValue}{item.unit}</div>
                                            <div className="statistices-category-name">{item.categoryName}</div>
                                            <div className="statistices-away-value">{item.awayValue}{item.unit}</div>
                                        </div>
                                        <div className="statistices-bar">
                                            <div className="statistices-barBg statistices-home">
                                                <div style={{ width: `${homePercentage}%` }} />
                                            </div>
                                            <div className="statistices-barBg statistices-away">
                                                <div style={{ width: `${awayPercentage}%` }} />
                                            </div>
                                        </div>
                                    </div>
                                    }
                                </>
                            );
                        })
                    }
                </div>
            </div>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { match } = state;
    return {
        match
    };
};

export default connect(mapStateToProps, mapDispatchToProps)(Statistics);