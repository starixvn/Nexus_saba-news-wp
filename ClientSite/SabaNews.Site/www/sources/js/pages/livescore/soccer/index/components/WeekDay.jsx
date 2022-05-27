import React from 'react';
import moment from 'moment';

const WeekDay = ({ isMobile, today, selectedDate, currentDate, onClick }) => {
    const date = moment(currentDate);
    const isActive = moment(selectedDate).isSame(date) && !window.isLivePage;
    const isToday = date.isSame(today);

    return (
        <div className={`week-date ${isActive ? 'active' : ''} ${isToday ? 'today' : ''}`}
            onClick={() => onClick(currentDate)}
        >
            <div className="day-of-week">
                {isMobile ? date.format('ddd') : date.format('dddd')}
            </div>
            <div className="date">{date.format('DD/MM')}</div>
        </div>
    );
};

export default WeekDay;