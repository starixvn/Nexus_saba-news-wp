import React, { Component } from 'react';

export default class Statistics extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        const { statistic } = this.props.match;
        if (!statistic || !statistic.homeStatistic || !statistic.awayStatistic) {
            return null;
        }
        const match = this.props.match;
        let statistices  = [];
        if(match !== null) {
            const {homeStatistic, awayStatistic} = this.props.match.statistic;
            statistices = [
                { categoryId: 1, categoryName: 'Kiểm soát bóng', homeValue: homeStatistic.controlRate, awayValue: awayStatistic.controlRate, unit: '%' },
                { categoryId: 2, categoryName: 'Số lần dứt điểm', homeValue: homeStatistic.onGoal + homeStatistic.missGoal, awayValue: awayStatistic.onGoal + awayStatistic.missGoal, unit: '' },
                { categoryId: 3, categoryName: 'Dứt điểm chính xác', homeValue: homeStatistic.onGoal, awayValue: awayStatistic.onGoal, unit: '' },
                { categoryId: 4, categoryName: 'Phạm lỗi', homeValue: homeStatistic.foul, awayValue: awayStatistic.foul, unit: '' },
                { categoryId: 5, categoryName: 'Thẻ Vàng', homeValue: homeStatistic.yellowCard, awayValue: awayStatistic.yellowCard, unit: '' },
                { categoryId: 6, categoryName: 'Thẻ Đỏ', homeValue: homeStatistic.redCard, awayValue: awayStatistic.redCard, unit: '' },
                { categoryId: 7, categoryName: 'Việt vị', homeValue: homeStatistic.offSide, awayValue: awayStatistic.offSide, unit: '' },
                { categoryId: 8, categoryName: 'Ném biên', homeValue: homeStatistic.outBall, awayValue: awayStatistic.outBall, unit: '' },
                { categoryId: 9, categoryName: 'Phạt góc', homeValue: homeStatistic.cornerKick, awayValue: awayStatistic.cornerKick, unit: '' },
                { categoryId: 10, categoryName: 'Đá phạt', homeValue: homeStatistic.freeKick, awayValue: awayStatistic.freeKick, unit: '' },
                { categoryId: 11, categoryName: 'Phạt đền', homeValue: homeStatistic.penaltyKick, awayValue: awayStatistic.penaltyKick, unit: '' },
            ];
        }
        return (
            <div className="statistices">
                <h2 className="heading-tab">Thống kê</h2>
                {
                    statistices.map(item => {
                        const homePercentage = (item.homeValue / (item.homeValue + item.awayValue)) * 100;
                        const awayPercentage = (item.awayValue / (item.homeValue + item.awayValue)) * 100;

                        return (
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
                        );
                    })
                }
            </div>
        );
    }
}