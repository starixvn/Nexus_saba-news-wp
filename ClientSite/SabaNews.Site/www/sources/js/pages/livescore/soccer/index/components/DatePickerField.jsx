import React from 'react';
import DatePicker from 'react-datepicker';

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
            <i className='date-picker-icon fa fa-calendar' onClick={onClick} ref={ref} />
        ));

        return (
            <DatePicker
                className={`datetime-picker`}
                dateFormat={'MMM dd, yyyy'}
                customInput={<CustomInput />}
                selected={this.state.date}
                {...rest}
            />
        );
    }
}