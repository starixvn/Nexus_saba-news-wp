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
                <div className={`btn-live ${window.isLiveMatchPage ? 'active' : ''}`}
                    onClick={() => {
                        window.isLiveMatchPage = true;
                        this.props.onClick();
                        this.props.navigate(Site.resolveClientUrl(`lich-thi-dau/bong-da/truc-tiep`));
                    }}
                >
                    <i className="icon-livescore icon-live-realtime"></i>
                    {
                        window.isLiveMatchPage && matches.length > 0 && <span>{matches.length}</span>
                    }
                    <div className="btn-live-realtime">{LangHelper.getResource('Live')}</div>
                </div>
                <WeekBar today={today} minDate={minDate} maxDate={maxDate} selectedDate={selectedDate}
                    getDateRanges={this.props.getDateRanges}
                    onClick={this.props.onClick}
                />
                <DatePickerField
                    getDateRanges={this.props.getDateRanges}
                    onChange={this.props.onClick}
                    navigate={this.props.navigate}
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