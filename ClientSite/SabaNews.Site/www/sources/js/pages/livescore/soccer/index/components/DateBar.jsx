import React, { PureComponent } from 'react';
import moment from 'moment';
import { useNavigate } from 'react-router-dom';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import DatePickerField from './DatePickerField';
import WeekBar from './WeekBar';
import Site from '@jsroot/common/site';
import LangHelper from '@jsroot/common/langHelper';

export const withNavigation = Component => {
    return props => <Component {...props} navigate={useNavigate()} />;
}

class DateBar extends PureComponent {
    render() {
        moment.updateLocale('vi', {
            weekdays: [
                'Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy'
            ]
        });
        moment.locale('vi');
        const { today, minDate, maxDate, selectedDate, matches } = this.props;

        return (
            <div className="livescore-soccer-date-bar">
                <div className={`btn-live ${window.isLiveMatch ? 'active' : ''}`}
                    onClick={() => {
                        window.isLiveMatch = true;
                        this.props.onClick();
                        this.props.navigate(Site.resolveClientUrl(`lich-thi-dau/bong-da/truc-tiep`));
                    }}
                >
                    <i className="icon-livescore icon-live-realtime"></i>
                    {
                        window.isLiveMatch && matches.length > 0 && <span>{matches.length}</span>
                    }
                    <div className="btn-live-realtime">{LangHelper.getResource('Live')}</div>
                </div>
                <WeekBar today={today} minDate={minDate} maxDate={maxDate} selectedDate={selectedDate}
                    getDateRanges={this.props.getDateRanges}
                    onClick={this.props.onClick}
                />
                <DatePickerField selectedDate={selectedDate}
                    minDate={minDate}
                    maxDate={maxDate}
                    onChange={date => {
                        window.isLiveMatch = false;
                        this.props.setSelectedDate(moment(date).format('YYYY-MM-DD'));
                        this.props.setDateRanges(this.props.getDateRanges(moment(date).format('YYYY-MM-DD')));
                        this.props.onClick(moment(date).format('YYYY-MM-DD'));
                        this.props.navigate(Site.resolveClientUrl(`lich-thi-dau/bong-da/${moment(date).format('YYYY-MM-DD')}`));
                    }}
                />
            </div>
        );
    }
}

const DateBarComponent = withNavigation(DateBar);

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { today, minDate, maxDate, selectedDate, matches } = state;
    return { today, minDate, maxDate, selectedDate, matches };
};

export default connect(mapStateToProps, mapDispatchToProps)(DateBarComponent);