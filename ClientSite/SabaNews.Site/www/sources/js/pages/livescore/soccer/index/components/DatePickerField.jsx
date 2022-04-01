import React from 'react';
import DatePicker, { registerLocale } from 'react-datepicker';
import vi from 'date-fns/locale/vi';
registerLocale('vi', vi);

export default class DatePickerField extends React.PureComponent {
    constructor(props) {
        super(props);

        this.state = {
            date: this.props.date
        };
    }

    componentDidUpdate(prevProps) {
        if (prevProps.date !== this.props.date) {
            this.setState({ date: this.props.date });
        }
    }

    render() {
        const { ...rest } = this.props;

        const CustomInput = React.forwardRef(({ value, onClick }, ref) => (
            <i className='date-picker-icon icon-livescore icon-live-calender' onClick={onClick} ref={ref} />
        ));

        return (
            <DatePicker
                className={`datetime-picker`}
                dateFormat={'MMM dd, yyyy'}
                locale="vi"
                customInput={<CustomInput />}
                selected={this.state.date}
                {...rest}
            />
        );
    }
}