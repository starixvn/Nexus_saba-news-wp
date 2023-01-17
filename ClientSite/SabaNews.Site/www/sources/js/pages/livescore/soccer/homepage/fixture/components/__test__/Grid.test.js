import React from 'react';
import { shallow } from 'enzyme';
import Grid from '../Grid';
import CommonFunctions from '@jsroot/common/common-functions';
import DateTimeService from '@jsroot/common/dateTimeService';
import moment from 'moment';

describe('Grid', () => {
    const defaultProps = {
        league: null,
        matches: [],
        isFinished: false
    };


    afterEach(() => {
        jest.clearAllMocks();
    });

    describe('handleSelectTab', () => {
        it('setState correctly when call function', () => {
            // Arrange
            const container = shallow(<Grid {...defaultProps} />);
            const instance = container.instance();
            instance.setState = jest.fn();
            // Act
            instance.handleSelectTab('fixture');

            // Assert
            expect(instance.setState).toHaveBeenCalled();
        });
    });

    describe('sortMatches', () => {
        it('return correctly when sort asc',() => {
            //Arrange
            const matches = [
                {
                    key: '2020-06-01',
                    text: moment('2020-06-01').format('dddd, DD/MM/YYYY'),
                    childs: []
                },
                {
                    key: '2020-06-02',
                    text:  moment('2020-06-02').format('dddd, DD/MM/YYYY'),
                    childs: []
                }
            ]
            const instance = shallow(<Grid {...defaultProps} />).instance();
            // Act
            const result = instance.sortMatches(matches, true);

            // Assert
            expect(result).toEqual(matches);
        });

        it('return correctly when sort desc',() => {
            //Arrange
            const matches = [
                {
                    key: '2020-06-01',
                    text: moment('2020-06-01').format('dddd, DD/MM/YYYY'),
                    childs: []
                },
                {
                    key: '2020-06-02',
                    text:  moment('2020-06-02').format('dddd, DD/MM/YYYY'),
                    childs: []
                }
            ]
            const instance = shallow(<Grid {...defaultProps} />).instance();
            // Act
            const result = instance.sortMatches(matches, false);

            // Assert
            expect(result).toEqual(matches.reverse());
        });
    });

    describe('filterPreMatches', () => {
        it('return correctly matches when  call function', () => {
            //Arrange
            const matches = [
                {
                    matchId: 1,
                    status:50,
                    endTime: '2023-09-01',
                },
                {
                    matchId: 2,
                    status:0,
                    endTime: '2023-11-01',
                }
            ]
            DateTimeService.getCurrentClientDateTime = jest.fn().mockReturnValue('2023-10-01');
            const instance = shallow(<Grid {...defaultProps} />).instance();
            // Act
            const result = instance.filterPreMatches(matches);

            // Assert
            expect(result).toEqual([{
                matchId: 2,
                status:0,
                endTime: '2023-11-01',
            }]);
        })
    });

    describe('handleClickLeague', () => {
        const { location } = window;

        beforeAll(() => {
            delete window.location;
            window.location = { href: '' };
        });

        afterAll(() => {
            window.location = location;
        });


        it('redirect correctly when ctrlKey = false', () => {
            // Arrange
            const event = {
                target: { className: '' },
                ctrlKey: false
            };

            window.open = jest.fn();
            const container = shallow(<Grid {...defaultProps} />);
            const instance = container.instance();
            CommonFunctions.getLeaguePageUrl = jest.fn().mockReturnValue('/lich-thi-dau/bong-da');
            // Act
            instance.handleClickLeague(event);

            // Assert
            expect(window.open).toHaveBeenCalledTimes(0);
            expect(window.location).toEqual('/lich-thi-dau/bong-da');
        });

        it('calls correctly when ctrlKey = true', () => {
           // Arrange
           const event = {
                target: { className: '' },
                ctrlKey: true
            };
            window.open = jest.fn();
            const container = shallow(<Grid {...defaultProps} />);
            const instance = container.instance();
            CommonFunctions.getLeaguePageUrl = jest.fn().mockReturnValue('/lich-thi-dau/bong-da');

            // Act
            instance.handleClickLeague(event);

            // Assert
            expect(window.open).toHaveBeenCalledWith('/lich-thi-dau/bong-da', '_blank');
        });
    });

    describe('renders', () => {
        it('return null when matches is empty', () => {
            // Arrange
            const container = shallow(<Grid {...defaultProps} />);
            const instance = container.instance();

            // Act
            const result = instance.render();

            // Assert
            expect(result).toEqual(null);
        });

        it('render correctly when matches have data', () => {
            // Arrange
            const matches = [
                {
                    matchId: 1,
                    status:50,
                    endTime: '2023-09-01',
                    startTime: '2023-09-01',
                    league: {leagueNameVN: 'league'}
                },
                {
                    matchId: 2,
                    status:0,
                    endTime: '2023-11-01',
                    startTime: '2023-11-01',
                    league: {leagueNameVN: 'league'}
                }
            ]
            const props = {
                ...defaultProps,
                matches: matches,
                league: {leagueNameVN: 'league'}
            }
            DateTimeService.getCurrentClientDateTime = jest.fn().mockReturnValue('2023-10-01');

            // Act
            const container = shallow(<Grid {...props} />);

            // Assert
            container.find('.round-name').simulate('click', {ctrlKey: true});
            expect(container).toMatchSnapshot();
        });
    });

});