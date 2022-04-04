import React, { PureComponent } from 'react';
import moment from 'moment';
import DatePickerField from './DatePickerField';
import WeekBar from './WeekBar';
import Site from '@jsroot/common/site';
import LangHelper from '@jsroot/common/langHelper';

export default class DateBar extends PureComponent {
    render() {
        moment.updateLocale('vi', {
            weekdays: [
                'Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy'
            ]
        });
        moment.locale('vi');
        const today = new Date(window.today);
        const minDate = new Date(2021, 0, 1);
        const maxDate = new Date(today.getFullYear() + 1, 12, 0);
        const matches = this.props.matches;

        return (
            <div className="livescore-soccer-date-bar">
                <div className={`btn-live ${window.isLiveMatch ? 'active' : ''}`}
                    onClick={() => window.location = Site.resolveClientUrl(`lich-thi-dau/bong-da/truc-tiep`)}
                >
                    <i className="icon-livescore icon-live-realtime"></i>
                    {
                        window.isLiveMatch && matches.length > 0 && <span>{matches.length}</span>
                    }
                    <div className="btn-live-realtime">{LangHelper.getResource('Live')}</div>
                </div>
                <WeekBar minDate={minDate} maxDate={maxDate} />
                <DatePickerField date={new Date(window.selectedEventDate)}
                    minDate={minDate}
                    maxDate={maxDate}
                    onChange={date => window.location = Site.resolveClientUrl(`lich-thi-dau/bong-da/${moment(date).format('YYYY-MM-DD')}`)}
                />
            </div>
        );
    }
}