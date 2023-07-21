import React, { PureComponent } from 'react';
import dayjs from 'dayjs';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import withNavigation from '@jsroot/components/withNavigation';
import WeekDay from './WeekDay';
import Site from '@jsroot/common/site';
import DateTimeService from '@jsroot/common/dateTimeService';


export class WeekBar extends PureComponent {
    onPrev = () => {
        const dateRanges = [...this.props.dateRanges];
        dateRanges.pop();
        dateRanges.unshift(DateTimeService.formatDateTime(dayjs(dateRanges[0]).subtract(1, 'days'), 'YYYY-MM-DD'));
        this.props.setDateRanges(dateRanges);
    };

    onNext = () => {
        const dateRanges = [...this.props.dateRanges];
        dateRanges.shift();
        dateRanges.push(DateTimeService.formatDateTime(dayjs(dateRanges[dateRanges.length - 1]).add(1, 'days'), 'YYYY-MM-DD'));
        this.props.setDateRanges(dateRanges);
    };

    onSelectDate = date => {
        window.isLivePage = false;
        this.props.setSelectedDate(date);
        this.props.onClick(date);
        this.props.navigate(Site.resolveClientUrl(`lich-thi-dau/bong-da/${date}`));
        const today = dayjs(DateTimeService.getCurrentClientDateTime()).format('YYYY-MM-DD');
        const selectedDate = dayjs(date).format('YYYY-MM-DD');
        if(dayjs(selectedDate).isBefore(today)) {
            document.title = "Kết quả bóng đá, tỷ số bóng đá ngày " + dayjs(date).format("DD/MM");
            document.getElementsByTagName('meta')["description"].content = `Lịch thi đấu bóng đá ngày ${dayjs(date).format("DD/MM")} mới nhất. Cập nhật lịch thi đấu và trực tiếp bóng đá ngày xx/yy liên tục trên SABA VN`;
        } else {
            document.head.querySelector('meta[name="description"]').content = "!23123123";
            document.title = `Xem kết quả bóng đá ngày ${dayjs(date).format("DD/MM")} chính xác nhất. Cập nhật tỉ số trận đấu ngày xx/yy đầy đủ và trực quan nhất cho độc giả trân SABA VN.`;
        }
    };

    render() {
        const { isMobile, today, minDate, maxDate, selectedDate, dateRanges } = this.props;

        return (
            <div className="livescore-soccer-week-bar">
                <button className="btn-prev-date" onClick={this.onPrev}
                    disabled={dateRanges.length === 0 || dayjs(dateRanges[0]).isSame(minDate)}
                >
                    <i className="icon-livescore icon-chevron-left" />
                </button>
                {
                    dateRanges.map((item, index) =>
                        <WeekDay key={index} isMobile={isMobile}
                            today={today}
                            selectedDate={selectedDate}
                            currentDate={item}
                            onClick={this.onSelectDate}
                        />
                    )
                }
                <button className="btn-next-date" onClick={this.onNext}
                    disabled={dateRanges.length === 0 || dayjs(dateRanges[dateRanges.length - 1]).isSame(maxDate)}
                >
                    <i className="icon-livescore icon-chevron-right" />
                </button>
            </div>
        );
    }
}

const WeekBarWithNavigation = withNavigation(WeekBar);

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { today, minDate, maxDate, selectedDate, dateRanges } = state;
    return { today, minDate, maxDate, selectedDate, dateRanges };
};

export default connect(mapStateToProps, mapDispatchToProps)(WeekBarWithNavigation);