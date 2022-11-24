import React from 'react';
import { shallow } from 'enzyme';
import toJson from 'enzyme-to-json';
import SkeletonGrid from '../SkeletonGrid';


describe('SkeletonGrid', () => {

    beforeEach(() => {
        const appContainer = document.createElement('div');
        appContainer.setAttribute('id', 'fixture-homepage');
        document.body.appendChild(appContainer);

        const mWidth = { getBoundingClientRect: jest.fn().mockReturnValueOnce({ offsetWidth: 500 }) };
        const spyFunc = jest.fn();
        Object.defineProperty(global.document, 'getElementById', { value: spyFunc, configurable: true });
        jest.spyOn(document, 'getElementById').mockImplementation((selector) => {
            switch (selector) {
                case 'fixture-homepage':
                return mWidth;
            }
        });
    })

    afterEach(() => {
        const appContainer = document.getElementById('fixture-homepage');
        if (appContainer) {
            document.body.removeChild(appContainer);
        }
    });

    it('renders correctly when isMobile = true', () => {
        // Act
        const props = {
            isMobile: true
        }
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