import React from 'react';
import { shallow } from 'enzyme';
import { Stages }  from '../Stages';
import CommonFunctions from '@jsroot/common/common-functions';
import DateTimeService from '@jsroot/common/dateTimeService';
import DataService from '@jsroot/common/dataService';

describe('Item', () => {
    const defaultProps = {
        stages: [
            {stageId: 1}, {stageId: 2}
        ],
        league: {teamId: 1, matchId: 1},
        votes: [],
        groupStages: [{stage: 1}, {stage: 2}]
    };

    afterEach(() => {
        jest.clearAllMocks();
    });

    describe('initStageId', () => {

        it('init state correctly when league current stage is null', () => {
            // Arrange
            const props = {
                ...defaultProps,
                league: {
                    season: { endTime: '2022-09-18 02:00:00' },
                    currentStageId: 0
                }
            }
            const container = shallow(<Stages {...props} />);
            const instance = container.instance();
            // Act
            const result = instance.initStageId();

            // Assert
            expect(result).toEqual(1)
        });

        it('init state correctly when league have current stage', () => {
            // Arrange
            const props = {
                ...defaultProps,
                league: {
                    season: { endTime: '2022-09-18 02:00:00' },
                    currentStageId: 1
                }
            }
            DateTimeService.getCurrentClientDateTime = jest.fn().mockReturnValue('2022-08-18 10:00:00');
            const container = shallow(<Stages {...props} />);
            const instance = container.instance();

            // Act
            const result = instance.initStageId();

            // Assert
            expect(result).toEqual(1)
        });
    });

    describe('handleSelectTab', () => {
        it('setState correctly when call function', () => {
            // Arrange
            const container = shallow(<Stages {...defaultProps} />);
            const instance = container.instance();

            // Act
            const result = instance.handleSelectTab(2);

            // Assert
            expect(instance.state.selectedStageId).toEqual(2)
        });
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


        it('calls correctly functions when ctrlKey = false', () => {
            // Arrange
            const event = {
                target: { className: '' },
                ctrlKey: false
            };
            window.open = jest.fn();
            const container = shallow(<Stages {...defaultProps} />);

            // Act
            container.find('.statistic span').first().simulate('click', event);

            // Assert
            expect(window.open).toHaveBeenCalledTimes(0);
            expect(window.location).toEqual('/world-cup-2022/su-kien-du-doan-sung-nhan-qua-khung/chi-tiet');
        });

        it('calls correctly functions when ctrlKey = true', () => {
            // Arrange
            const event = {
                target: { className: '' },
                ctrlKey: true
            };
            window.open = jest.fn();
            const container = shallow(<Stages {...defaultProps} />);

            // Act
            container.find('.statistic span').first().simulate('click', event);

            // Assert
            expect(window.open).toHaveBeenCalledWith('/world-cup-2022/su-kien-du-doan-sung-nhan-qua-khung/chi-tiet', '_blank');
        });
    });

    describe('renders', () => {
        it('render correctly when there is only one group stage', () => {
            // Arrange
            const props = {
                ...defaultProps,
                groupStages: [{stage: 1, matches: [{matchId: 1}]}]
            }
            const container = shallow(<Stages {...props} />);
            const instance = container.instance();

            // Act
            const result = instance.render();

            // Assert
            expect(result).toMatchSnapshot();
        });

        it('render correctly when there are more than one group stage', () => {
            // Arrange
            const props = {
                ...defaultProps,
                groupStages: [{key: 1, stage: 1, matches: [{matchId: 1}]}, {key: 2, stage: 2, matches: [{matchId: 2}]}]
            }
            const container = shallow(<Stages {...props} />);
            const instance = container.instance();

            // Act
            const result = instance.render();

            // Assert
            expect(result).toMatchSnapshot();
        });
    });
});