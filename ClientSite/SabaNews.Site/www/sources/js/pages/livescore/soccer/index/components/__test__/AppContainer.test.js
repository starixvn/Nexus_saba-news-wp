import React from 'react';
import { shallow } from 'enzyme';
import moment from 'moment';
import toJson from 'enzyme-to-json';
import { AppContainer } from '../AppContainer';
import DateTimeService from '@jsroot/common/dateTimeService';

describe('AppContainer', () => {
    const defaultProps = {
        selectedDate: '2022-05-25',
        searchKey: '',
        matches: null,
        majorLeagues: [],
        totalLiveMatches: 0,
        getMajorLeagues: jest.fn(),
        getMatchesByDate: jest.fn(),
        setSearchKey: jest.fn(),
        getLiveMatches: jest.fn(),
        setDateRanges: jest.fn(),
        setMatches: jest.fn()
    };

    beforeEach(() => {
        DateTimeService.formatDateTime = jest.fn(date => date);
        DateTimeService.getClientTimezone = jest.fn(() => 7);
    });

    afterEach(() => {
        jest.clearAllMocks();
    });

    describe('constructor', () => {
        afterEach(() => {
            window.innerWidth = 0;
        });

        it('inital correctly state when innerWidth < 768', () => {
            // Arrange
            window.innerWidth = 600;

            // Act
            const instance = shallow(<AppContainer />).instance();

            // Assert
            expect(instance.state).toEqual({ isMobile: true });
        });

        it('inital correctly state when innerWidth = 768', () => {
            // Arrange
            window.innerWidth = 768;

            // Act
            const instance = shallow(<AppContainer />).instance();

            // Assert
            expect(instance.state).toEqual({ isMobile: true });
        });

        it('inital correctly state when innerWidth > 768', () => {
            // Arrange
            window.innerWidth = 960;

            // Act
            const instance = shallow(<AppContainer />).instance();

            // Assert
            expect(instance.state).toEqual({ isMobile: false });
        });
    });

    describe('componentDidMount', () => {
        it('should call correctly functions', () => {
            // Arrange
            window.addEventListener = jest.fn();

            const wrapper = shallow(<AppContainer {...defaultProps} />);
            const instance = wrapper.instance();
            instance.handleResize = jest.fn();
            instance.getDateRanges = jest.fn(() => ['2022-05-24', '2022-05-25', '2022-05-26']);
            instance.getMatches = jest.fn();

            // Act
            instance.componentDidMount();

            // Assert
            expect(defaultProps.setDateRanges).toHaveBeenCalledWith(['2022-05-24', '2022-05-25', '2022-05-26']);
            expect(defaultProps.getMajorLeagues).toHaveBeenCalledTimes(2);
            expect(window.addEventListener).toHaveBeenCalledWith('resize', instance.handleResize);
            expect(instance.getMatches).toHaveBeenCalledWith('2022-05-25');
        });
    });

    describe('componentWillUnmount', () => {
        it('should call correctly functions', () => {
            // Arrange
            window.removeEventListener = jest.fn();

            const wrapper = shallow(<AppContainer />);
            const instance = wrapper.instance();

            // Act
            instance.componentWillUnmount();

            // Assert
            expect(window.removeEventListener).toHaveBeenCalledWith('resize', instance.handleResize);
        });
    });

    describe('handleResize', () => {
        it('should update correctly state when isMobile = false and window.innerWidth < 768', () => {
            // Arrange
            window.innerWidth = 767;
            const wrapper = shallow(<AppContainer />);
            const instance = wrapper.instance();
            instance.setState({ isMobile: false });

            // Act
            instance.handleResize();

            // Assert
            expect(instance.state).toEqual({ isMobile: true });
        });

        it('should update correctly state when isMobile = false and window.innerWidth = 768', () => {
            // Arrange
            window.innerWidth = 768;
            const wrapper = shallow(<AppContainer />);
            const instance = wrapper.instance();
            instance.setState({ isMobile: false });

            // Act
            instance.handleResize();

            // Assert
            expect(instance.state).toEqual({ isMobile: true });
        });

        it('do nothing when isMobile = true and window.innerWidth < 768', () => {
            // Arrange
            window.innerWidth = 767;
            const wrapper = shallow(<AppContainer />);
            const instance = wrapper.instance();
            instance.setState({ isMobile: true });
            instance.setState = jest.fn();

            // Act
            instance.handleResize();

            // Assert
            expect(instance.setState).toHaveBeenCalledTimes(0);
            expect(instance.state).toEqual({ isMobile: true });
        });

        it('do nothing when isMobile = true and window.innerWidth = 768', () => {
            // Arrange
            window.innerWidth = 768;
            const wrapper = shallow(<AppContainer />);
            const instance = wrapper.instance();
            instance.setState({ isMobile: true });
            instance.setState = jest.fn();

            // Act
            instance.handleResize();

            // Assert
            expect(instance.setState).toHaveBeenCalledTimes(0);
            expect(instance.state).toEqual({ isMobile: true });
        });

        it('should update correctly state when isMobile = true and window.innerWidth > 768', () => {
            // Arrange
            window.innerWidth = 769;
            const wrapper = shallow(<AppContainer />);
            const instance = wrapper.instance();
            instance.setState({ isMobile: true });

            // Act
            instance.handleResize();

            // Assert
            expect(instance.state).toEqual({ isMobile: false });
        });
    });

    describe('getDateRanges', () => {
        it('should return correctly dates when selectedDate = minDate', () => {
            // Arrange
            const props = {
                ...defaultProps,
                minDate: '2022-05-25'
            };
            const wrapper = shallow(<AppContainer {...props} />);
            const instance = wrapper.instance();

            // Act
            const dates = instance.getDateRanges('2022-05-25');

            // Assert
            expect(dates.length).toEqual(5);
            expect(moment(dates[0]).format('YYYY-MM-DD')).toEqual(moment('2022-05-25').format('YYYY-MM-DD'));
            expect(moment(dates[1]).format('YYYY-MM-DD')).toEqual(moment('2022-05-26').format('YYYY-MM-DD'));
            expect(moment(dates[2]).format('YYYY-MM-DD')).toEqual(moment('2022-05-27').format('YYYY-MM-DD'));
            expect(moment(dates[3]).format('YYYY-MM-DD')).toEqual(moment('2022-05-28').format('YYYY-MM-DD'));
            expect(moment(dates[4]).format('YYYY-MM-DD')).toEqual(moment('2022-05-29').format('YYYY-MM-DD'));
        });

        it('should return correctly dates when selectedDate = minDate + 1', () => {
            // Arrange
            const props = {
                ...defaultProps,
                minDate: '2022-05-25'
            };
            const wrapper = shallow(<AppContainer {...props} />);
            const instance = wrapper.instance();

            // Act
            const dates = instance.getDateRanges('2022-05-26');

            // Assert
            expect(dates.length).toEqual(5);
            expect(moment(dates[0]).format('YYYY-MM-DD')).toEqual(moment('2022-05-25').format('YYYY-MM-DD'));
            expect(moment(dates[1]).format('YYYY-MM-DD')).toEqual(moment('2022-05-26').format('YYYY-MM-DD'));
            expect(moment(dates[2]).format('YYYY-MM-DD')).toEqual(moment('2022-05-27').format('YYYY-MM-DD'));
            expect(moment(dates[3]).format('YYYY-MM-DD')).toEqual(moment('2022-05-28').format('YYYY-MM-DD'));
            expect(moment(dates[4]).format('YYYY-MM-DD')).toEqual(moment('2022-05-29').format('YYYY-MM-DD'));
        });

        it('should return correctly dates when selectedDate > minDate + 1 and selectedDate = maxDate', () => {
            // Arrange
            const props = {
                ...defaultProps,
                minDate: '2022-05-25',
                maxDate: '2022-05-30'
            };
            const wrapper = shallow(<AppContainer {...props} />);
            const instance = wrapper.instance();

            // Act
            const dates = instance.getDateRanges('2022-05-30');

            // Assert
            expect(dates.length).toEqual(5);
            expect(moment(dates[0]).format('YYYY-MM-DD')).toEqual(moment('2022-05-26').format('YYYY-MM-DD'));
            expect(moment(dates[1]).format('YYYY-MM-DD')).toEqual(moment('2022-05-27').format('YYYY-MM-DD'));
            expect(moment(dates[2]).format('YYYY-MM-DD')).toEqual(moment('2022-05-28').format('YYYY-MM-DD'));
            expect(moment(dates[3]).format('YYYY-MM-DD')).toEqual(moment('2022-05-29').format('YYYY-MM-DD'));
            expect(moment(dates[4]).format('YYYY-MM-DD')).toEqual(moment('2022-05-30').format('YYYY-MM-DD'));
        });

        it('should return correctly dates when selectedDate > minDate + 1 and selectedDate = maxDate - 1', () => {
            // Arrange
            const props = {
                ...defaultProps,
                minDate: '2022-05-25',
                maxDate: '2022-05-30'
            };
            const wrapper = shallow(<AppContainer {...props} />);
            const instance = wrapper.instance();

            // Act
            const dates = instance.getDateRanges('2022-05-29');

            // Assert
            expect(dates.length).toEqual(5);
            expect(moment(dates[0]).format('YYYY-MM-DD')).toEqual(moment('2022-05-26').format('YYYY-MM-DD'));
            expect(moment(dates[1]).format('YYYY-MM-DD')).toEqual(moment('2022-05-27').format('YYYY-MM-DD'));
            expect(moment(dates[2]).format('YYYY-MM-DD')).toEqual(moment('2022-05-28').format('YYYY-MM-DD'));
            expect(moment(dates[3]).format('YYYY-MM-DD')).toEqual(moment('2022-05-29').format('YYYY-MM-DD'));
            expect(moment(dates[4]).format('YYYY-MM-DD')).toEqual(moment('2022-05-30').format('YYYY-MM-DD'));
        });

        it('should return correctly dates when selectedDate > minDate + 1 and selectedDate < maxDate - 1', () => {
            // Arrange
            const props = {
                ...defaultProps,
                minDate: '2022-05-25',
                maxDate: '2022-05-30'
            };
            const wrapper = shallow(<AppContainer {...props} />);
            const instance = wrapper.instance();

            // Act
            const dates = instance.getDateRanges('2022-05-27');

            // Assert
            expect(dates.length).toEqual(5);
            expect(moment(dates[0]).format('YYYY-MM-DD')).toEqual(moment('2022-05-25').format('YYYY-MM-DD'));
            expect(moment(dates[1]).format('YYYY-MM-DD')).toEqual(moment('2022-05-26').format('YYYY-MM-DD'));
            expect(moment(dates[2]).format('YYYY-MM-DD')).toEqual(moment('2022-05-27').format('YYYY-MM-DD'));
            expect(moment(dates[3]).format('YYYY-MM-DD')).toEqual(moment('2022-05-28').format('YYYY-MM-DD'));
            expect(moment(dates[4]).format('YYYY-MM-DD')).toEqual(moment('2022-05-29').format('YYYY-MM-DD'));
        });
    });

    describe('getMatches', () => {
        it('should call correctly functions when searchKey.length = 0', () => {
            // Arrange
            const wrapper = shallow(<AppContainer {...defaultProps} />);
            const instance = wrapper.instance();

            // Act
            instance.getMatches('2022-05-25');

            // Assert
            expect(defaultProps.setSearchKey).toHaveBeenCalledTimes(0);
            expect(defaultProps.setMatches).toHaveBeenCalledWith(null);
            expect(defaultProps.getLiveMatches).toHaveBeenCalledTimes(2);
        });

        it('should call correctly functions when searchKey.length > 0', () => {
            // Arrange
            const props = { ...defaultProps, searchKey: 'key' };
            const wrapper = shallow(<AppContainer {...props} />);
            const instance = wrapper.instance();

            // Act
            instance.getMatches('2022-05-25');

            // Assert
            expect(props.setSearchKey).toHaveBeenCalledWith('');
            expect(props.setMatches).toHaveBeenCalledWith(null);
            expect(props.getLiveMatches).toHaveBeenCalledTimes(2);
        });

        it('should call correctly functions when window.isLivePage = true', () => {
            // Arrange
            const props = { ...defaultProps, searchKey: 'key' };
            const wrapper = shallow(<AppContainer {...props} />);
            const instance = wrapper.instance();

            // Act
            instance.getMatches('2022-05-25');

            // Assert
            expect(props.setSearchKey).toHaveBeenCalledWith('');
            expect(props.setMatches).toHaveBeenCalledWith(null);
            expect(props.getLiveMatches).toHaveBeenCalledTimes(2);
            expect(props.getMatchesByDate).toHaveBeenCalledWith({ eventDate: '2022-05-25', timezone: 7 });
        });
    });

    describe('render', () => {
        it('renders correctly when matches = null', () => {
            // Arrange
            const container = shallow(<AppContainer />);
            const instance = container.instance();
            instance.getMatches = jest.fn();
            instance.getDateRanges = jest.fn();

            // Act
            const result = shallow(instance.render());

            // Assert
            expect(toJson(result)).toMatchSnapshot();
        });

        it('renders correctly when matches != null', () => {
            // Arrange
            const props = { ...defaultProps, matches: [] };
            const container = shallow(<AppContainer {...props} />);
            const instance = container.instance();
            instance.getMatches = jest.fn();
            instance.getDateRanges = jest.fn();

            // Act
            const result = shallow(instance.render());

            // Assert
            expect(toJson(result)).toMatchSnapshot();
        });
    });
});