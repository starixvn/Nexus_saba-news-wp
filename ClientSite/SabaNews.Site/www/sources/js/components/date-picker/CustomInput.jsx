import React from 'react';
import { Form } from 'react-bootstrap';

export default class CustomInput extends React.PureComponent {
    render() {
        const { disabled, onClick, value } = this.props;

        return (
            <>
                <Form.Control as='input' className="shadow-none"
                    readOnly disabled={disabled} onClick={onClick} value={value}
                />
                <i className='date-picker-icon fa fa-calendar' onClick={onClick} />
            </>
        );
    }
}