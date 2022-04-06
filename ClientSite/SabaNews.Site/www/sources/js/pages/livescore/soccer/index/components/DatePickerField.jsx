import React, { PureComponent } from 'react';
import DatePicker, { registerLocale } from 'react-datepicker';
import vi from 'date-fns/locale/vi';
registerLocale('vi', vi);

export default class DatePickerField extends PureComponent {
    constructor(props) {
        super(props);

        this.wrapper = null;

        this.state = {
            open: false
        };
    }

    componentDidMount() {
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
                    onChange={date => this.props.onChange(date)}
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