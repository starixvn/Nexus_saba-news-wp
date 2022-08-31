import React from 'react';
import { shallow } from 'enzyme';
import toJson from 'enzyme-to-json';
import { AppContainer } from '../AppContainer';
import CommonFunctions from '@jsroot/common/common-functions';
import DateTimeService from '@jsroot/common/dateTimeService';
import Site from '@jsroot/common/site';

describe('AppContainer', () => {
    const defaultProps = {
        league: null,
        matches: null,
        getMatches: jest.fn()
    };


    afterEach(() => {
        jest.clearAllMocks();
    });

    describe('componentDidMount', () => {
        it('calls correctly functions', () => {
            // Arrange
            const container = shallow(<AppContainer {...defaultProps} />);
            const instance = container.instance();

            // Act
            instance.componentDidMount();

            // Assert
            expect(defaultProps.getMatches).toHaveBeenCalledTimes(2);
        });
    })

    describe('checkIsFinished', () => {
        it('return as expected when season finished', () => {
            // Arrange
            const league = {
                season: { endTime: '2022-08-18 02:00:00' }
            }
            const container = shallow(<AppContainer {...defaultProps} />);
            const instance = container.instance();
            DateTimeService.getCurrentClientDateTime = jest.fn().mockReturnValue('2022-08-18 10:00:00');
            // Act
            const result = instance.checkIsFinished(league);

            // Assert
            expect(result).toEqual(true);
        });

        it('return as expected when season does not finish', () => {
            // Arrange
            const league = {
                season: { endTime: '2022-08-19 05:00:00' }
            }
            const container = shallow(<AppContainer {...defaultProps} />);
            const instance = container.instance();
            DateTimeService.getCurrentClientDateTime = jest.fn().mockReturnValue('2022-08-18 10:00:00');
            // Act
            const result = instance.checkIsFinished(league);

            // Assert
            expect(result).toEqual(false);
        });
    });

    describe('getMatches', () => {
        it('return correctly when season finished', () => {
            // Arrange
            const matches = [{ matchId: 1 }, { matchId: 2 }];
            const league = {
                season: { endTime: '2022-08-19 05:00:00' },
                currentStageId: 0
            }
            const props = {
                ...defaultProps,
                matches: matches,
                league: league
            }
            const container = shallow(<AppContainer {...props} />);
            const instance = container.instance();
            instance.checkIsFinished = jest.fn().mockReturnValue(true);
            // Act
            const result = instance.getMatches(league);

            // Assert
            expect(result).toEqual(matches.reverse());
        });

        it('return correctly when stage < QuarterfinalsStage', () => {
            // Arrange
            const matches = [{ matchId: 1, stage: { stageId: 0 } }, { matchId: 2, stage: { stageId: 0 } }];
            const league = {
                season: { endTime: '2022-08-19 05:00:00' },
                currentStageId: 0
            }
            const props = {
                ...defaultProps,
                matches: matches,
                league: league
            }
            const container = shallow(<AppContainer {...props} />);
            const instance = container.instance();
            instance.checkIsFinished = jest.fn().mockReturnValue(false);
            // Act
            const result = instance.getMatches(league);

            // Assert
            expect(result).toEqual(matches);
        });

        it('return correctly when stage >= 30539', () => {
            // Arrange
            const matches = [{ matchId: 1, stage: { stageId: 30537 } }, { matchId: 2, stage: { stageId: 0 } }];
            const league = {
                season: { endTime: '2022-08-19 05:00:00' },
                currentStageId: 30540
            }
            const props = {
                ...defaultProps,
                matches: matches,
                league: league
            }
            const container = shallow(<AppContainer {...props} />);
            const instance = container.instance();
            instance.checkIsFinished = jest.fn().mockReturnValue(false);
            // Act
            const result = instance.getMatches(league);

            // Assert
            expect(result).toEqual([{ matchId: 2, stage: { stageId: 0 } }]);
        });
    });

    describe('renders', () => {
        it('render skeleton when match is null', () => {
            // Arrange
            const container = shallow(<AppContainer {...defaultProps} />);
            const instance = container.instance();
            // Act
            const result = instance.render();

            // Assert
            expect(result).toMatchSnapshot();
        });

        it('return null when match is empty', () => {
            // Arrange
            const props = {
                ...defaultProps,
                matches: []
            }
            const container = shallow(<AppContainer {...props} />);
            const instance = container.instance();
            // Act
            const result = instance.render();

            // Assert
            expect(result).toEqual(null);
        });

        it('render correctly ', () => {
            // Arrange
            const matches = [{ matchId: 1, stage: { stageId: 30537 } }, { matchId: 2, stage: { stageId: 0 } }];
            const league = {
                season: { endTime: '2022-08-19 05:00:00' },
                currentStageId: 30540
            }
            const props = {
                ...defaultProps,
                matches: matches,
                league: league
            }
            const container = shallow(<AppContainer {...props} />);
            const instance = container.instance();
            instance.getMatches = jest.fn().mockReturnValue(matches);
            instance.checkIsFinished = jest.fn().mockReturnValue(false);
            // Act
            const result = instance.render();

            // Assert
            expect(result).toMatchSnapshot();
        });
    });
})