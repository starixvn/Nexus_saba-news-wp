import React, { Component } from 'react';

export default class Statistics extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        if (!this.props.soccerMatch || !this.props.soccerMatch.homeTeam || !this.props.soccerMatch.awayTeam) {
            return null;
        }
        const match = this.props.soccerMatch;
        let statistices  = [];
        if(match !== null) {
            const homeTeam = match.homeTeam;
            const awayTeam = match.awayTeam
            statistices = [
                { categoryId: 1, categoryName: 'Kiểm soát bóng', homeValue: homeTeam.controlRate, awayValue: awayTeam.controlRate, unit: '%' },
                { categoryId: 2, categoryName: 'Số lần dứt điểm', homeValue: homeTeam.shoot, awayValue: awayTeam.shoot, unit: '' },
                { categoryId: 3, categoryName: 'Dứt điểm chính xác', homeValue: homeTeam.onGoal, awayValue: awayTeam.onGoal, unit: '' },
                { categoryId: 4, categoryName: 'Phạm lỗi', homeValue: homeTeam.foul, awayValue: homeTeam.foul, unit: '' },
                { categoryId: 5, categoryName: 'Thẻ Vàng', homeValue: homeTeam.yellowCard, awayValue: awayTeam.yellowCard, unit: '' },
                { categoryId: 6, categoryName: 'Thẻ Đỏ', homeValue: homeTeam.redCard, awayValue: awayTeam.redCard, unit: '' },
                { categoryId: 7, categoryName: 'Việt vị', homeValue: homeTeam.OffSide, awayValue: awayTeam.OffSide, unit: '' },
                { categoryId: 8, categoryName: 'Ném biên', homeValue: homeTeam.outBall, awayValue: awayTeam.outBall, unit: '' },
                { categoryId: 9, categoryName: 'Phạt góc', homeValue: homeTeam.cornerKick, awayValue: awayTeam.cornerKick, unit: '' },
                { categoryId: 10, categoryName: 'Đá phạt', homeValue: homeTeam.freeKick, awayValue: awayTeam.freeKick, unit: '' },
                { categoryId: 11, categoryName: 'Phạt đền', homeValue: homeTeam.penaltyKick, awayValue: awayTeam.penaltyKick, unit: '' },
                
            ];
        }
        
        return (
            <div className="statistices">
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