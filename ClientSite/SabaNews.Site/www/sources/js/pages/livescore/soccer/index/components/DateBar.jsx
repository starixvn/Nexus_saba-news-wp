import React from 'react';
import { useNavigate } from 'react-router-dom';
import DatePickerField from './DatePickerField';
import WeekBar from './WeekBar';
import Site from '@jsroot/common/site';
import LangHelper from '@jsroot/common/langHelper';

export const withNavigation = Component => {
    return props => <Component {...props} navigate={useNavigate()} />;
}

const DateBar = props => {
    const { isMobile, totalMatches } = props;

    return (
        <div className="livescore-soccer-date-bar">
            <div className={`btn-live ${window.isLivePage ? 'active' : ''}`}
                onClick={() => {
                    window.isLivePage = true;
                    props.onClick();
                    props.navigate(Site.resolveClientUrl(`lich-thi-dau/bong-da/truc-tiep`));
                }}
            >
                <i className="icon-livescore icon-live-realtime"></i>
                <div className="btn-live-realtime">
                    {isMobile ? 'Live' : LangHelper.getResource('Live')}
                    {
                        window.isLivePage && totalMatches > 0 &&
                        <span className="badge-live">{totalMatches > 99 ? '99+' : totalMatches}</span>
                    }
                </div>
            </div>
            <WeekBar isMobile={isMobile}
                getDateRanges={props.getDateRanges}
                onClick={props.onClick}
            />
            <DatePickerField
                getDateRanges={props.getDateRanges}
                onChange={props.onClick}
                navigate={props.navigate}
            />
        </div>
    );
};

const DateBarComponent = withNavigation(DateBar);

export default DateBarComponent;