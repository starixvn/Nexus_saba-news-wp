import React from 'react';
import { shallow } from 'enzyme';
import toJson from 'enzyme-to-json';
import Grid from '../Grid';
import CommonFunctions from '@jsroot/common/common-functions';
import DateTimeService from '@jsroot/common/dateTimeService';
import Site from '@jsroot/common/site';

describe('Grid', () => {
    const defaultProps = {
        standings: null
    };

    afterEach(() => {
        jest.clearAllMocks();
    });

    describe('initSelectedLeagueId', () => {
        it('return correct default stageId', () => {
            // Arrange
            const props = {
                ...defaultProps,
                standings:[
                    {
                        league:{leagueId: 1},
                        standing: []
                    },
                    {
                        league:{leagueId: 2},
                        standing: [{stageId: 1}]
                    }
                ]
            }
            const container = shallow(<Grid {...props} />);
            const instance = container.instance();
            // Act
            const result = instance.initSelectedLeagueId();

            // Assert
            expect(result).toEqual(2);
        })
    });

    describe('getLeagueStanding', () => {
        it('return correct standing when call function', () => {
            // Arrange
            const props = {
                ...defaultProps,
                standings:[
                    {
                        league:{leagueId: 1},
                        standing: []
                    },
                    {
                        league:{leagueId: 2},
                        standing: [{stageId: 1}]
                    }
                ]
            }
            const container = shallow(<Grid {...props} />);
            const instance = container.instance();
            // Act
            const result = instance.getLeagueStanding();

            // Assert
            expect(result).toEqual({
                league:{leagueId: 2},
                standing: [{stageId: 1}]
            });
        })
    });

    describe('renders', () => {
        it('render correct', () => {
            // Arrange
            const props = {
                ...defaultProps,
                standings:[
                    {
                        league:{leagueId: 1},
                        standing: []
                    },
                    {
                        league:{leagueId: 2, leagueNameVN:'league name'},
                        standing: [{stageId: 1}, {stageId: 2}]
                    }
                ]
            }
            // Act
            const container = shallow(<Grid {...props} />);
            container.find('.season-standings select').simulate('change', {target: {value: 'value'}});

            // Assert
            expect(container).toMatchSnapshot();
        })
    });
})