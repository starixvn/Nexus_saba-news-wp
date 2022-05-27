import React from 'react';
import { shallow } from 'enzyme';
import toJson from 'enzyme-to-json';
import { SearchBar } from '../SearchBar';

describe('SearchBar', () => {
    let defaultProps = {
        searchKey: '',
        setSearchKey: jest.fn()
    };

    afterEach(() => {
        jest.clearAllMocks();
    });

    it('renders correctly', () => {
        // Act
        const container = shallow(<SearchBar {...defaultProps} />);

        // Assert
        expect(toJson(container)).toMatchSnapshot();
    });

    describe('onChange', () => {
        it('should call correctly functions', () => {
            // Arrange
            const event = {
                target: { value: 'key' }
            };
            const container = shallow(<SearchBar {...defaultProps} />);
            const instance = container.instance();

            // Act
            instance.onChange(event);

            // Assert
            expect(defaultProps.setSearchKey).toHaveBeenCalledWith('key');
        });
    });
});