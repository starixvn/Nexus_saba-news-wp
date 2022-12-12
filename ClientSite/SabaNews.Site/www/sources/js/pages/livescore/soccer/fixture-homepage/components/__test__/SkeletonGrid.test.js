import React from 'react';
import { shallow } from 'enzyme';
import toJson from 'enzyme-to-json';
import SkeletonGrid from '../SkeletonGrid';


describe('SkeletonGrid', () => {
    beforeEach(() => {
        const appContainer = document.createElement('div');
        appContainer.setAttribute('id', 'fixture-homepage');
        document.body.appendChild(appContainer);
    });

    afterEach(() => {
        jest.clearAllMocks();
    });

    it('renders correctly when isMobile = true', () => {
        // Act
        const props = {
            isMobile: true
        }
        Object.defineProperty(HTMLElement.prototype, 'offsetWidth', { configurable: true, value: 500 })
        const container = shallow(<SkeletonGrid {...props}/>);

        // Assert
        expect(toJson(container)).toMatchSnapshot();
    });

    it('renders correctly when isMobile = false', () => {
        // Act
        const props = {
            isMobile: false
        }
        const container = shallow(<SkeletonGrid {...props}/>);

        // Assert
        expect(toJson(container)).toMatchSnapshot();
    });
});