import React from 'react';
import { shallow } from 'enzyme';
import toJson from 'enzyme-to-json';
import Grid  from '../Grid';
import CommonFunctions from '@jsroot/common/common-functions';
import DateTimeService from '@jsroot/common/dateTimeService';
import Site from '@jsroot/common/site';


describe('Grid', () => {
    const defaultProps = {
        matches: [],
        votes: []
    };

    afterEach(() => {
        jest.clearAllMocks();
    });

    describe('checkMatchResult', () => {
        it('return correctly when call function', () => {
            // Arrange
            const match = {
                matchId: 1,
                stage: {stageId: 30537},
                homeTeamTotalScore: 2,
                awayTeamTotalScore: 0,
                status: 50,
                homeTeam: {teamId: 1},
                awayTeam: {teamId: 2}
            };
            const vote = {matchId: 1, teamId: 1}
            const container = shallow(<Grid {...defaultProps} />);
            const instance = container.instance();

            // Act
            const result = instance.checkMatchResult(match, vote);

            // Assert
            expect(result).toEqual({score: 10, status: true});
        });
    });

    describe('renders', () => {
        it('render correctly when call function', () => {
            // Arrange
            const match = {
                matchId: 1,
                stage: {stageId: 30537},
                homeTeamTotalScore: 2,
                awayTeamTotalScore: 0,
                status: 50,
                homeTeam: {teamId: 1},
                awayTeam: {teamId: 2}
            };
            const vote = {matchId: 1, teamId: 1}
            const props = {
                ...defaultProps,
                matches: [{matchId: 1}, {matchId: 2}, {matchId: 4}],
                votes: [{matchId: 1}, {matchId: 3}, {matchId: 2}]
            }
            const container = shallow(<Grid {...props} />);
            const instance = container.instance();
            instance.checkMatchResult = jest.fn().mockReturnValueOnce(null).mockReturnValueOnce({score: 10, status: true});
            // Act
            const result = instance.render(match, vote);

            // Assert
            expect(result).toMatchSnapshot()
        });
    });
})