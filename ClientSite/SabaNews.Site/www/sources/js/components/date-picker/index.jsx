import React from 'react';
import DatePicker from 'react-datepicker';
import CustomInput from './CustomInput';

export default class DatePickerField extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            selected: this.props.selected
        };
    }

    componentDidUpdate(prevProps) {
        if (prevProps.selected !== this.props.selected) {
            this.setState({ selected: this.props.selected });
        }
    }

    render() {
        const { className, selected, ...rest } = this.props;

        return (
            <DatePicker
                className={`datetime-picker ${className}`}
                dateFormat={'MMM dd, yyyy'}
                locale="vi"
                customInput={<CustomInput />}
                selected={this.state.selected}
                {...rest}
            />
        );
    }
}