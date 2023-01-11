import React from 'react';
import { shallow } from 'enzyme';
import { AppContainer } from '../AppContainer';
import DateTimeService from '@jsroot/common/dateTimeService';

describe('AppContainer', () => {
    const defaultProps = {
        league: null,
        matches: null,
        getMatches: jest.fn()
    };


    afterEach(() => {
        jest.clearAllMocks();
    });

    describe('constructor', () => {
        afterEach(() => {
            window.innerWidth = 0;
        });

        it('inital correctly state when innerWidth < 1024', () => {
            // Arrange
            window.innerWidth = 600;

            // Act
            const instance = shallow(<AppContainer {...defaultProps} />).instance();

            // Assert
            expect(instance.state.isMobile).toEqual(true);
        });

        it('inital correctly state when innerWidth = 1024', () => {
            // Arrange
            window.innerWidth = 1024;

            // Act
            const instance = shallow(<AppContainer {...defaultProps} />).instance();

            // Assert
            expect(instance.state.isMobile).toEqual(true);
        });

        it('inital correctly state when innerWidth > 1024', () => {
            // Arrange
            window.innerWidth = 1026;

            // Act
            const instance = shallow(<AppContainer {...defaultProps} />).instance();

            // Assert
            expect(instance.state.isMobile).toEqual(false);
        });
    })

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

    describe('componentWillUnmount', () => {
        it('calls correctly functions', () => {
            // Arrange
            window.removeEventListener = jest.fn();

            const container = shallow(<AppContainer {...defaultProps} />);
            const instance = container.instance();

            // Act
            instance.componentWillUnmount();

            // Assert
            expect(window.removeEventListener).toHaveBeenCalledWith('resize', instance.handleResize);
        });
    });

    describe('handleResize', () => {
        it('updates correctly state when isMobile = false and window.innerWidth < 1024', () => {
            // Arrange
            window.innerWidth = 767;
            const container = shallow(<AppContainer {...defaultProps} />);
            const instance = container.instance();
            instance.setState({ isMobile: false });

            // Act
            instance.handleResize();

            // Assert
            expect(instance.state.isMobile).toEqual(true);
        });

        it('updates correctly state when isMobile = false and window.innerWidth = 1024', () => {
            // Arrange
            window.innerWidth = 1024;
            const container = shallow(<AppContainer {...defaultProps} />);
            const instance = container.instance();
            instance.setState({ isMobile: false });

            // Act
            instance.handleResize();

            // Assert
            expect(instance.state.isMobile).toEqual(true);
        });

        it('do nothing when isMobile = true and window.innerWidth < 1024', () => {
            // Arrange
            window.innerWidth = 767;
            const container = shallow(<AppContainer {...defaultProps} />);
            const instance = container.instance();
            instance.setState({ isMobile: true });
            instance.setState = jest.fn();

            // Act
            instance.handleResize();

            // Assert
            expect(instance.setState).toHaveBeenCalledTimes(0);
            expect(instance.state.isMobile).toEqual(true);
        });

        it('do nothing when isMobile = true and window.innerWidth = 1024', () => {
            // Arrange
            window.innerWidth = 1024;
            const container = shallow(<AppContainer {...defaultProps} />);
            const instance = container.instance();
            instance.setState({ isMobile: true });
            instance.setState = jest.fn();

            // Act
            instance.handleResize();

            // Assert
            expect(instance.setState).toHaveBeenCalledTimes(0);
            expect(instance.state.isMobile).toEqual(true);
        });

        it('updates correctly state when isMobile = true and window.innerWidth > 1024', () => {
            // Arrange
            window.innerWidth = 1025;
            const container = shallow(<AppContainer {...defaultProps} />);
            const instance = container.instance();
            instance.setState({ isMobile: true });

            // Act
            instance.handleResize();

            // Assert
            expect(instance.state.isMobile).toEqual(false);
        });
    });

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

    describe('handleClickLeague', () => {
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
                ctrlKey: false
            };
            const league = { leagueId: 12036, leagueNameVN: 'Euro'};
            window.open = jest.fn();
            const container = shallow(<AppContainer {...defaultProps} />);
            const instance = container.instance();
            DateTimeService.formatDateTime = jest.fn().mockReturnValue('2022-06-01');

            // Act
            instance.handleClickLeague(event, league);

            // Assert
            expect(window.open).toHaveBeenCalledTimes(0);
            expect(window.location).toEqual('/lich-thi-dau/bong-da/euro/0/2022-06-01/12036');
        });

        it('redirect correctly when league has season', () => {
            // Arrange
            const event = {
                target: { className: 'livescore' }
            };
            window.open = jest.fn();
            const league = { leagueId: 12036, leagueNameVN: 'Euro', currentSeasonId: 123, season:{seasonId: 123}};
            DateTimeService.formatDateTime = jest.fn().mockReturnValue('2022-06-01');
            const container = shallow(<AppContainer {...defaultProps} />);
            const instance = container.instance();
            // Act
            instance.handleClickLeague(event, league);

            // Assert
            expect(window.open).toHaveBeenCalledTimes(0);
            expect(window.location).toEqual('/lich-thi-dau/bong-da/euro/123/2022-06-01/12036');
        });

        it('calls correctly when ctrlKey = true', () => {
            // Arrange
            const event = {
                ctrlKey: true
            };
            window.open = jest.fn();
            const league = { leagueId: 12036, leagueNameVN: 'Euro', season: {seasonId: 123}};
            DateTimeService.formatDateTime = jest.fn().mockReturnValue('2022-06-01');
            const container = shallow(<AppContainer {...defaultProps} />);
            const instance = container.instance();
            // Act
            instance.handleClickLeague(event, league);

            // Assert
            expect(window.open).toHaveBeenCalledWith('/lich-thi-dau/bong-da/euro/123/2022-06-01/12036', '_blank');
        });

        it('calls correctly when ctrlKey = false', () => {
            // Arrange
            const event = {
                ctrlKey: false
            };
            window.open = jest.fn();
            const league = { leagueId: 12036, leagueNameVN: 'Euro', season: {seasonId:123}};
            DateTimeService.formatDateTime = jest.fn().mockReturnValue('2022-06-01');
            const container = shallow(<AppContainer {...defaultProps} />);
            const instance = container.instance();

            // Act
            instance.handleClickLeague(event, league);

            // Assert
            expect(window.open).toHaveBeenCalledTimes(0);
            expect(window.location).toEqual('/lich-thi-dau/bong-da/euro/123/2022-06-01/12036');
        });
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

        it('calls correctly when ctrlKey = true', () => {
            // Arrange
            const event = {
                ctrlKey: true
            };
            window.open = jest.fn();
            const container = shallow(<AppContainer {...defaultProps} />);
            const instance = container.instance();
            // Act
            instance.handleClickFixture(event);

            // Assert
            expect(window.open).toHaveBeenCalledWith('lich-thi-dau/bong-da', '_blank');
        });

        it('calls correctly when ctrlKey = false', () => {
            // Arrange
            const event = {
                ctrlKey: false
            };
            window.open = jest.fn();
            const container = shallow(<AppContainer {...defaultProps} />);
            const instance = container.instance();

            // Act
            instance.handleClickFixture(event);

            // Assert
            expect(window.open).toHaveBeenCalledTimes(0);
            expect(window.location).toEqual('lich-thi-dau/bong-da');
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
            expect(result.matches).toEqual(matches.reverse());
        });

        it('return correctly when stage < QuarterfinalsStage', () => {
            // Arrange
            const matches = [{ matchId: 1, status:0, stage: { stageId: 0 } }, { matchId: 2, status:0, stage: { stageId: 0 } }];
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
            expect(result.matches).toEqual(matches);
        });

        it('return correctly when stage >= 30539', () => {
            // Arrange
            const matches = [{ matchId: 1, status: 0, stage: { stageId: 30537 } }, { matchId: 2, status:0 , stage: { stageId: 0 } }];
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
            expect(result.matches).toEqual([{ matchId: 1, status: 0, stage: { stageId: 30537 }}, { matchId: 2, status: 0, stage: { stageId: 0 }}]);
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

        it('return correct when match is empty', () => {
            // Arrange
            const props = {
                ...defaultProps,
                matches: [],
                league: {leagueId: 1, season:{endTime: '2022-10-10'}}
            };
            const container = shallow(<AppContainer {...props} />);
            const instance = container.instance();
            instance.checkIsFinished = jest.fn();
            // Act
            const result = instance.render();

            // Assert
            expect(result).toMatchSnapshot();
        });

        it('render correctly when matches has data', () => {
            // Arrange
            const matches = [{ matchId: 1, status: 0, stage: { stageId: 30537 } }, { matchId: 2, status: 0, stage: { stageId: 0 } }];
            const league = {
                season: { endTime: '2022-08-19 05:00:00' },
                currentStageId: 30540,
                leagueNameEN: "name"
            }
            const props = {
                ...defaultProps,
                matches: matches,
                league: league
            }
            // Act
            const container = shallow(<AppContainer {...props} />);

            // Assert
            container.find('.logo-wc-homepage .text').simulate('click', {ctrlKey: false}, props.league);
            expect(container).toMatchSnapshot();
        });
    });
})