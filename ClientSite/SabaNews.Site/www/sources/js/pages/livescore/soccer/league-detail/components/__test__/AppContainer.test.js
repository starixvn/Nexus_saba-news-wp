import React from 'react';
import { shallow } from 'enzyme';
import toJson from 'enzyme-to-json';
import { AppContainer } from '../AppContainer';
import CommonFunctions from '@jsroot/common/common-functions';
import DateTimeService from '@jsroot/common/dateTimeService';
import Site from '@jsroot/common/site';

describe('AppContainer', () => {
    const defaultProps = {
        majorLeagues: [],
        league: null,
        stages: [],
        preMatches: null,
        postMatches: null,
        getFavLeagues: jest.fn(),
        getMatchesBySeason: jest.fn(),
        getLeagues: jest.fn(),
        navigate: jest.fn(),
        setFavLeagues: jest.fn()
    };

    beforeEach(() => {
        window.getMatchByStageParams = {
            selectedTab: ''
        };
        DateTimeService.getClientTimezone = jest.fn(() => 7);
        CommonFunctions.getLeagueName = jest.fn(() => 'World Cup');
    });

    afterEach(() => {
        window.getMatchByStageParams = null;
        jest.clearAllMocks();
    });

    describe('constructor', () => {
        it('inital correctly state when selectedTab = fixtures', () => {
            // Arrange
            window.getMatchByStageParams = {
                selectedTab: 'fixtures'
            };

            // Act
            const instance = shallow(<AppContainer {...defaultProps} />).instance();

            // Assert
            expect(instance.state).toEqual({
                flagToggleFavLeagueIcon: false,
                selectedTab: 'fixtures',
                selectedResultsTab: false,
                selectedStandingsTab: false,
                selectedFixtureTab: true
            });
        });

        it('inital correctly state when selectedTab = results', () => {
            // Arrange
            window.getMatchByStageParams = {
                selectedTab: 'results'
            };

            // Act
            const instance = shallow(<AppContainer {...defaultProps} />).instance();

            // Assert
            expect(instance.state).toEqual({
                flagToggleFavLeagueIcon: false,
                selectedTab: 'results',
                selectedResultsTab: true,
                selectedStandingsTab: false,
                selectedFixtureTab: false
            });
        });

        it('inital correctly state when selectedTab = standings', () => {
            // Arrange
            window.getMatchByStageParams = {
                selectedTab: 'standings'
            };

            // Act
            const instance = shallow(<AppContainer {...defaultProps} />).instance();

            // Assert
            expect(instance.state).toEqual({
                flagToggleFavLeagueIcon: false,
                selectedTab: 'standings',
                selectedResultsTab: false,
                selectedStandingsTab: true,
                selectedFixtureTab: false
            });
        });

        it('inital correctly state when selectedTab != (fixtures, results, standings)', () => {
            // Arrange
            window.getMatchByStageParams = {
                selectedTab: 'abc'
            };

            // Act
            const instance = shallow(<AppContainer {...defaultProps} />).instance();

            // Assert
            expect(instance.state).toEqual({
                flagToggleFavLeagueIcon: false,
                selectedTab: 'general',
                selectedResultsTab: false,
                selectedStandingsTab: false,
                selectedFixtureTab: false
            });
        });
    });

    describe('componentDidMount', () => {
        it('calls correctly functions', () => {
            // Arrange
            window.getMatchByStageParams = {
                selectedTab: ''
            };
            const container = shallow(<AppContainer {...defaultProps} />);
            const instance = container.instance();
            instance.getMatches = jest.fn();

            // Act
            instance.componentDidMount();

            // Assert
            expect(defaultProps.getFavLeagues).toHaveBeenCalledTimes(2);
            expect(defaultProps.getLeagues).toHaveBeenCalledTimes(2);
            expect(instance.getMatches).toHaveBeenCalledTimes(1);
        });
    });

    describe('getMatches', () => {
        it('calls correctly function', () => {
            // Arrange
            window.getMatchByStageParams = {
                selectedTab: '',
                leagueId: 1,
                seasonId: 1
            };
            const container = shallow(<AppContainer {...defaultProps} />);
            const instance = container.instance();

            // Act
            instance.getMatches();

            // Assert
            expect(defaultProps.getMatchesBySeason).toHaveBeenCalledWith({
                selectedTab: '',
                leagueId: 1,
                seasonId: 1,
                timezone: 7
            });
        });
    });

    describe('handleSelectTab', () => {
        beforeEach(() => {
            window.getMatchByStageParams = {
                selectedTab: '',
                leagueId: 1,
                seasonId: 1,
                date: '2022-05-25'
            };
            Site.toSlug = jest.fn(() => 'world-cup');
            Site.resolveClientUrl = jest.fn(url => url);
        });

        afterEach(() => {
            window.getMatchByStageParams = null;
            jest.clearAllMocks();
        });

        it('updates correctly state when selectedTab = fixtures', () => {
            // Arrange
            const container = shallow(<AppContainer {...defaultProps} />);
            const instance = container.instance();

            // Act
            instance.handleSelectTab('fixtures');

            // Assert
            expect(instance.state).toEqual({
                flagToggleFavLeagueIcon: false,
                selectedTab: 'fixtures',
                selectedResultsTab: false,
                selectedStandingsTab: false,
                selectedFixtureTab: true
            });
            expect(defaultProps.navigate).toHaveBeenCalledWith('lich-thi-dau/bong-da/world-cup/1/2022-05-25/1/fixtures');
        });

        it('updates correctly state when selectedTab = results', () => {
            // Arrange
            const container = shallow(<AppContainer {...defaultProps} />);
            const instance = container.instance();

            // Act
            instance.handleSelectTab('results');

            // Assert
            expect(instance.state).toEqual({
                flagToggleFavLeagueIcon: false,
                selectedTab: 'results',
                selectedResultsTab: true,
                selectedStandingsTab: false,
                selectedFixtureTab: false
            });
            expect(defaultProps.navigate).toHaveBeenCalledWith('lich-thi-dau/bong-da/world-cup/1/2022-05-25/1/results');
        });

        it('updates correctly state when selectedTab = standings', () => {
            // Arrange
            const container = shallow(<AppContainer {...defaultProps} />);
            const instance = container.instance();

            // Act
            instance.handleSelectTab('standings');

            // Assert
            expect(instance.state).toEqual({
                flagToggleFavLeagueIcon: false,
                selectedTab: 'standings',
                selectedResultsTab: false,
                selectedStandingsTab: true,
                selectedFixtureTab: false
            });
            expect(defaultProps.navigate).toHaveBeenCalledWith('lich-thi-dau/bong-da/world-cup/1/2022-05-25/1/standings');
        });
    });

    describe('renderTabs', () => {
        it('return as expected when selectedTab = fixtures and preMatches = null', () => {
            // Arrange
            const container = shallow(<AppContainer {...defaultProps} />);
            const instance = container.instance();
            instance.setState({ selectedTab: 'fixtures' });

            // Act
            const result = shallow(instance.renderTabs());

            // Assert
            expect(toJson(result)).toMatchSnapshot();
        });

        it('return as expected when selectedTab = fixtures and preMatches != null', () => {
            // Arrange
            const props = { ...defaultProps, preMatches: [] };
            const container = shallow(<AppContainer {...props} />);
            const instance = container.instance();
            instance.setState({ selectedTab: 'fixtures' });

            // Act
            const result = shallow(instance.renderTabs());

            // Assert
            expect(toJson(result)).toMatchSnapshot();
        });

        it('return as expected when selectedTab = results and postMatches = null', () => {
            // Arrange
            const container = shallow(<AppContainer {...defaultProps} />);
            const instance = container.instance();
            instance.setState({ selectedTab: 'results', selectedResultsTab: true });

            // Act
            const result = shallow(instance.renderTabs());

            // Assert
            expect(toJson(result)).toMatchSnapshot();
        });

        it('return as expected when selectedTab = results and postMatches != null', () => {
            // Arrange
            const props = { ...defaultProps, postMatches: [] };
            const container = shallow(<AppContainer {...props} />);
            const instance = container.instance();
            instance.setState({ selectedTab: 'results', selectedResultsTab: true });

            // Act
            const result = shallow(instance.renderTabs());

            // Assert
            expect(toJson(result)).toMatchSnapshot();
        });

        it('return as expected when selectedTab = standings and league = null', () => {
            // Arrange
            const container = shallow(<AppContainer {...defaultProps} />);
            const instance = container.instance();
            instance.setState({ selectedTab: 'standings', selectedStandingsTab: true });

            // Act
            const result = shallow(instance.renderTabs());

            // Assert
            expect(toJson(result)).toMatchSnapshot();
        });

        it('return as expected when selectedTab = standings and league != null and hasSeasonStandings = false', () => {
            // Arrange
            const props = {
                ...defaultProps,
                league: { hasSeasonStandings: false }
            };
            const container = shallow(<AppContainer {...props} />);
            const instance = container.instance();
            instance.setState({ selectedTab: 'standings', selectedStandingsTab: true });

            // Act
            const result = shallow(instance.renderTabs());

            // Assert
            expect(toJson(result)).toMatchSnapshot();
        });

        it('return as expected when selectedTab = standings and league != null and hasSeasonStandings = true', () => {
            // Arrange
            const props = {
                ...defaultProps,
                league: { hasSeasonStandings: true }
            };
            const container = shallow(<AppContainer {...props} />);
            const instance = container.instance();
            instance.setState({ selectedTab: 'standings', selectedStandingsTab: true });

            // Act
            const result = shallow(instance.renderTabs());

            // Assert
            expect(toJson(result)).toMatchSnapshot();
        });
    });

    it('onToggleFavLeaguesIcon setState correctly', () => {
        // Arrange
        const instance = shallow(<AppContainer {...defaultProps} />).instance();

        // Act
        instance.onToggleFavLeaguesIcon();

        // Assert
        expect(instance.state.flagToggleFavLeagueIcon).toEqual(true);
    });

    it('onUpdateFavLeague call correctly function', () => {
        // Arrange
        const instance = shallow(<AppContainer {...defaultProps} />).instance();

        // Act
        instance.onUpdateFavLeague();

        // Assert
        expect(defaultProps.setFavLeagues).toHaveBeenCalled();
    });

    describe('render', () => {
        it('renders correctly when league = null', () => {
            // Act
            const container = shallow(<AppContainer {...defaultProps} />);

            // Assert
            expect(toJson(container)).toMatchSnapshot();
        });

        it('renders correctly when league != null', () => {
            // Arrange
            const props = {
                ...defaultProps,
                league: { hasSeasonStandings: true }
            };

            // Act
            const container = shallow(<AppContainer {...props} />);

            // Assert
            expect(toJson(container)).toMatchSnapshot();
            expect(document.title).toEqual('World Cup');
        });
    });
});