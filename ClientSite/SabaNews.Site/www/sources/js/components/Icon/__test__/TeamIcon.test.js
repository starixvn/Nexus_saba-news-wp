import React from 'react';
import { shallow } from 'enzyme';
import TeamIcon from '../TeamIcon';
import CommonFunctions from '@jsroot/common/common-functions'

describe('TeamIcon', () => {
    let defaultProps = {
        className: 'class',
        src: null
    };

    afterEach(() => {
        jest.clearAllMocks();
    });

    it('render correctly when src is null', () => {
        // Arrange & Act
        const container = shallow(<TeamIcon {...defaultProps} />)

       // Assert
       expect(container).toMatchSnapshot();
    });

    it('render correctly when have src', () => {
        // Arrange & Act
        const props = {
            ...defaultProps,
            src: 'src'
        }
        const container = shallow(<TeamIcon {...props} />)

       // Assert
       expect(container).toMatchSnapshot();
    });
})