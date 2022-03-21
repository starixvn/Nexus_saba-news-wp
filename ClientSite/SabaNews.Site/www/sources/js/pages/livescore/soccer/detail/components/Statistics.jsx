import React, { Component } from 'react';

export default class Statistics extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        const statistices = [
            { categoryId: 1, categoryName: 'Kiểm soát bóng', homeValue: 57, awayValue: 43, unit: '%' },
            { categoryId: 2, categoryName: 'Số lần dứt điểm', homeValue: 14, awayValue: 2, unit: '' },
            { categoryId: 3, categoryName: 'Sút trúng đích', homeValue: 6, awayValue: 0, unit: '' },
            { categoryId: 4, categoryName: 'Dứt điểm ra ngoài', homeValue: 8, awayValue: 2, unit: '' },
            { categoryId: 5, categoryName: 'Phạt góc', homeValue: 5, awayValue: 2, unit: '' },
            { categoryId: 6, categoryName: 'Việt vị', homeValue: 1, awayValue: 2, unit: '' },
            { categoryId: 7, categoryName: 'Thủ môn Cứu thua', homeValue: 0, awayValue: 1, unit: '' },
            { categoryId: 8, categoryName: 'Phạm lỗi', homeValue: 11, awayValue: 6, unit: '' },
            { categoryId: 9, categoryName: 'Thẻ Vàng', homeValue: 1, awayValue: 1, unit: '' },
            { categoryId: 10, categoryName: 'Tổng số đường chuyền', homeValue: 360, awayValue: 268, unit: '' },
            { categoryId: 11, categoryName: 'Số đường chuyền hoàn thành', homeValue: 300, awayValue: 237, unit: '' },
            { categoryId: 12, categoryName: 'Tấn công', homeValue: 72, awayValue: 83, unit: '' },
            { categoryId: 13, categoryName: 'Tấn công nguy hiểm', homeValue: 49, awayValue: 40, unit: '' }
        ];
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