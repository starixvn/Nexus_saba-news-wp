import React from 'react';
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

const DateBar = props => {
    const { isMobile, matches } = props;

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
                        window.isLivePage && matches.length > 0 && <span className="badge-live">{matches.length}</span>
                    }
                </div>
            </div>
            <WeekBar
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

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { isMobile, matches } = state;
    return { isMobile, matches };
};

export default connect(mapStateToProps, mapDispatchToProps)(DateBarComponent);