import React, { PureComponent } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import moment from 'moment';
import { ActionCreators } from '../actions';
import DatePicker, { registerLocale } from 'react-datepicker';
import vi from 'date-fns/locale/vi';
import Site from '@jsroot/common/site';

export class DatePickerField extends PureComponent {
    constructor(props) {
        super(props);

        this.wrapper = null;

        this.state = {
            open: false
        };
    }

    componentDidMount() {
        registerLocale('vi', vi);
        document.addEventListener('click', this.handleClickOutside);
    }

    componentWillUnmount() {
        document.removeEventListener('click', this.handleClickOutside);
    }

    handleClickOutside = event => {
        const clickOutside = this.wrapper && !this.wrapper.contains(event.target);
        if (clickOutside && this.state.open) {
            this.setState({ open: false });
        }
    }

    handleToggle = () => {
        this.setState(prevState => {
            return { ...prevState, open: !prevState.open };
        });
    };

    closeOnScroll = () => {
        if (this.state.open) {
            this.setState({ open: false });
        }
    };

    onChange = date => {
        this.setState({ open: false });
        date = moment(date).format('YYYY-MM-DD');
        window.isLiveMatchPage = false;
        this.props.setSelectedDate(date);

        if (this.props.dateRanges.findIndex(x => x === date) === -1) {
            this.props.setDateRanges(this.props.getDateRanges(date));
        }

        this.props.onChange(date);
        this.props.navigate(Site.resolveClientUrl(`lich-thi-dau/bong-da/${date}`));
    };

    render() {
        const { selectedDate, minDate, maxDate } = this.props;
        const open = this.state.open;

        const CustomInput = React.forwardRef(({ value, onClick }, ref) => (
            <i onClick={onClick} ref={ref} />
        ));

        return (
            <div className="date-picker" ref={el => this.wrapper = el}>
                <DatePicker
                    className={`datetime-picker`}
                    dateFormat={'MMM dd, yyyy'}
                    locale="vi"
                    customInput={<CustomInput />}
                    selected={new Date(selectedDate)}
                    minDate={new Date(minDate)}
                    maxDate={new Date(maxDate)}
                    onChange={this.onChange}
                    open={open}
                    closeOnScroll={this.closeOnScroll}
                />
                <span className={`date-picker-icon icon-livescore icon-live-calender ${open ? 'active' : ''}`}
                    onClick={this.handleToggle}
                />
            </div>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { today, minDate, maxDate, selectedDate, dateRanges } = state;
    return { today, minDate, maxDate, selectedDate, dateRanges };
};

export default connect(mapStateToProps, mapDispatchToProps)(DatePickerField);