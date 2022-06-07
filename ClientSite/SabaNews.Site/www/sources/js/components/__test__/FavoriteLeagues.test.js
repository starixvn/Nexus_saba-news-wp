import React from 'react';
import { shallow } from 'enzyme';
import toJson from 'enzyme-to-json';
import { FavoriteLeagues } from '../FavoriteLeagues';
import CommonFunctions from '@jsroot/common/common-functions';
import DateTimeService from '@jsroot/common/dateTimeService';

describe('FavoriteLeagues', () => {
    let defaultProps = {
        leagues: []
    };

    afterEach(() => {
        jest.clearAllMocks();
    });

    it('render correctly', () => {
        // Arrange
        const props = {
            leagues: [
                {leagueId: 1, leagueNameVN: 'name', icon:'icon', currentSeasonId: 1},
                {leagueId: 2, leagueNameVN: 'name', icon:'icon'}
            ]
        }
         // Act
         const container = shallow(<FavoriteLeagues {...props} />);

         // Assert
         expect(toJson(container)).toMatchSnapshot();
    });

    describe('handleClick', () => {
        const { location } = window;

        beforeAll(() => {
            delete window.location;
            window.location = { href: '' };
        });

        afterAll(() => {
            window.location = location;
        });

        it('redirect correctly when league has no season', () => {
            // Arrange
            const event = {
                target: { className: 'icon-livescore' },
                ctrlKey: false
            };
            const props = {
                leagues: [
                    {leagueId: 1, leagueNameVN: 'name', icon:'icon', currentSeasonId: 1},
                    {leagueId: 2, leagueNameVN: 'name', icon:'icon'}
                ]
            }
            window.open = jest.fn();
            const container = shallow(<FavoriteLeagues {...props} />);
            DateTimeService.formatDateTime = jest.fn().mockReturnValue('2022-06-01');
            CommonFunctions.getLeagueName = jest.fn().mockReturnValue('world-cup');

            // Act
            container.find('.leagues-item').last().simulate('click', event)

            // Assert
            expect(window.open).toHaveBeenCalledTimes(0);
            expect(window.location).toEqual('/lich-thi-dau/bong-da/world-cup/0/2022-06-01/2');
        });

        it('redirect correctly when league has season', () => {
            // Arrange
            const event = {
                target: { className: 'icon-livescore' },
                ctrlKey: false
            };
            const props = {
                leagues: [
                    {leagueId: 1, leagueNameVN: 'name', icon:'icon', currentSeasonId: 1},
                    {leagueId: 2, leagueNameVN: 'name', icon:'icon'}
                ]
            }
            window.open = jest.fn();
            const container = shallow(<FavoriteLeagues {...props} />);
            DateTimeService.formatDateTime = jest.fn().mockReturnValue('2022-06-01');
            CommonFunctions.getLeagueName = jest.fn().mockReturnValue('world-cup');

            // Act
            container.find('.leagues-item').first().simulate('click', event)

            // Assert
            expect(window.open).toHaveBeenCalledTimes(0);
            expect(window.location).toEqual('/lich-thi-dau/bong-da/world-cup/1/2022-06-01/1');
        });

        it('calls correctly when ctrlKey = true', () => {
           // Arrange
           const event = {
                target: { className: 'icon-livescore' },
                ctrlKey: true
            };
            const props = {
                leagues: [
                    {leagueId: 1, leagueNameVN: 'name', icon:'icon', currentSeasonId: 1},
                    {leagueId: 2, leagueNameVN: 'name', icon:'icon'}
                ]
            }
            window.open = jest.fn();
            const container = shallow(<FavoriteLeagues {...props} />);
            DateTimeService.formatDateTime = jest.fn().mockReturnValue('2022-06-01');
            CommonFunctions.getLeagueName = jest.fn().mockReturnValue('world-cup');

            // Act
            container.find('.leagues-item').first().simulate('click', event)

            // Assert
            expect(window.open).toHaveBeenCalledWith('/lich-thi-dau/bong-da/world-cup/1/2022-06-01/1', '_blank');
        });

    });
});