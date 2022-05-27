import React from 'react';
import { shallow } from 'enzyme';
import toJson from 'enzyme-to-json';
import League from '../League';
import Site from '@jsroot/common/site';
import CommonFunctions from '@jsroot/common/common-functions';

describe('Input', () => {
    const defaultProps = {
        league: {
            leagueId: 1,
            icon: '',
            matches: [
                { matchId: 1 },
                { matchId: 2 }
            ]
        },
        searchKey: '',
        selectedDate: '2022-05-25'
    };

    beforeAll(() => {
        Site.resolveClientUrl = jest.fn(url => url);
        CommonFunctions.getLeagueNameWithStage = jest.fn(() => 'World Cup');
        CommonFunctions.getLeagueName = jest.fn(() => 'World Cup');
        Site.toSlug = jest.fn(() => 'world-cup');
    });

    afterAll(() => {
        jest.clearAllMocks();
    });

    it('renders correctly when searchKey.length = 0', () => {
        // Act
        const container = shallow(<League {...defaultProps} />);

        // Assert
        expect(toJson(container)).toMatchSnapshot();
    });

    it('renders correctly when searchKey.length > 0', () => {
        // Arrange
        const props = { ...defaultProps, searchKey: 'world' };

        // Act
        const container = shallow(<League {...props} />);

        // Assert
        expect(toJson(container)).toMatchSnapshot();
    });

    describe('onClick', () => {
        const { location } = window;

        beforeAll(() => {
            delete window.location;
            window.location = { href: '' };
        });

        afterAll(() => {
            window.location = location;
        });

        it('calls correctly functions when ctrlKey = false', () => {
            // Arrange
            window.open = jest.fn();
            const container = shallow(<League {...defaultProps} />);

            // Act
            container.find('div.soccer-match-grid-item-header').simulate('click', { ctrlKey: false });

            // Assert
            expect(window.open).toHaveBeenCalledTimes(0);
            expect(window.location).toEqual('lich-thi-dau/bong-da/world-cup/0/2022-05-25/1');
        });

        it('calls correctly functions when season != null', () => {
            // Arrange
            window.open = jest.fn();
            const props = {
                ...defaultProps,
                league: {
                    ...defaultProps.league,
                    season: { seasonId: 1 }
                }
            };
            const container = shallow(<League {...props} />);

            // Act
            container.find('div.soccer-match-grid-item-header').simulate('click', { ctrlKey: false });

            // Assert
            expect(window.open).toHaveBeenCalledTimes(0);
            expect(window.location).toEqual('lich-thi-dau/bong-da/world-cup/1/2022-05-25/1');
        });

        it('calls correctly functions when ctrlKey = true', () => {
            // Arrange
            window.open = jest.fn();
            const container = shallow(<League {...defaultProps} />);

            // Act
            container.find('div.soccer-match-grid-item-header').simulate('click', { ctrlKey: true });

            // Assert
            expect(window.open).toHaveBeenCalledWith('lich-thi-dau/bong-da/world-cup/0/2022-05-25/1', '_blank');
        });
    });
});