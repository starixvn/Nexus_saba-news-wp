import React from 'react';
import { shallow } from 'enzyme';
import toJson from 'enzyme-to-json';
import { AppContainer } from '../AppContainer';
import CommonFunctions from '@jsroot/common/common-functions';

describe('AppContainer', () => {
    afterEach(() => {
        jest.clearAllMocks();
    });

    describe('componentDidMount', () => {
        it('call correctly functions', () => {
            // Arrange
            const props = {
                getMatchDetail: jest.fn(),
                getMajorLeagues: jest.fn()
            };
            const container = shallow(<AppContainer {...props} />).instance();

            // Act
            container.componentDidMount();

            // Assert
            expect(props.getMatchDetail).toHaveBeenCalled();
            expect(props.getMajorLeagues).toHaveBeenCalled();
        });
    });

    describe('render', () => {
        it('renders correctly when match is null', () => {
            // Arrange
            const props = {
                getMatchDetail: jest.fn(),
                getMajorLeagues: jest.fn()
            };
            const container = shallow(<AppContainer {...props} />);


            // Assert
            expect(container.type()).toEqual(null);
        });

        it('renders correctly when match is not null', () => {
            // Arrange
            const props = {
                match: {
                    generalInfo: {
                        matchId: 1,
                        league: {
                            leagueId: 1,
                            leagueNameEN: 'Premier League',
                            leagueNameVN: 'Ngoại hạng Anh',
                        },
                        homeTeam: {
                            teamId: 1,
                            teamNameEN: 'Arsenal',
                        },
                        awayTeam: {
                            teamId: 2,
                            teamNameEN: 'Everton',
                        }
                    }
                },
                getMatchDetail: jest.fn(),
                getMajorLeagues: jest.fn()
            };
            CommonFunctions.getTeamName = jest.fn(() => props.match.generalInfo.homeTeam);
            const container = shallow(<AppContainer {...props} />);
            const instance = container.instance();

            // Act
            const result = shallow(instance.render());

            // Assert
            expect(toJson(result)).toMatchSnapshot();
        })
    });

});



