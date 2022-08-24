import React from 'react';
import { shallow } from 'enzyme';
import toJson from 'enzyme-to-json';
import Grid from '../Grid';
import CommonFunctions from '@jsroot/common/common-functions';
import DateTimeService from '@jsroot/common/dateTimeService';
import Site from '@jsroot/common/site';

describe('Grid', () => {
    const defaultProps = {
        league: null,
        matches: [],
        isFinished: false
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
            const instance = shallow(<Grid {...defaultProps} />).instance();

            // Assert
            expect(instance.state.isScroll).toEqual(true);
        });

        it('inital correctly state when innerWidth = 1024', () => {
            // Arrange
            window.innerWidth = 1024;

            // Act
            const instance = shallow(<Grid {...defaultProps} />).instance();

            // Assert
            expect(instance.state.isScroll).toEqual(true);
        });

        it('inital correctly state when innerWidth > 1024', () => {
            // Arrange
            window.innerWidth = 1026;

            // Act
            const instance = shallow(<Grid {...defaultProps} />).instance();

            // Assert
            expect(instance.state.isScroll).toEqual(false);
        });
    })

    describe('componentDidMount', () => {
        it('init state correctly when season finished', () => {
            // Arrange
            const initWindowWidth = 1026;
            window.innerWidth = 1026;
            const showItems = Math.ceil(initWindowWidth / 260);
            const props = {
                ...defaultProps,
                isFinished: true
            }
            window.addEventListener = jest.fn();
            const instance = shallow(<Grid {...props} />).instance();
            instance.handleResize = jest.fn();
            // Act
            instance.componentDidMount();

            // Assert
            expect(instance.state.start).toEqual(0);
            expect(instance.state.end).toEqual(showItems);
            expect(window.addEventListener).toHaveBeenCalledWith('resize', instance.handleResize);
        });

        it('init state correctly when season did not finish and last post-match is in last page index', () => {
            // Arrange
            const initWindowWidth = 500;
            window.innerWidth = initWindowWidth;
            const showItems = Math.ceil(initWindowWidth / 260);
            const matches = [
                { matchId: 1, status: 50 }, { matchId: 2, status: 50 }, { matchId: 3, status: 50 },
                { matchId: 2, status: 50 }, { matchId: 3, status: 50 },
                { matchId: 7, status: 50 }, { matchId: 8, status: 50 }, { matchId: 9, status: 50 }
            ];
            const props = {
                ...defaultProps,
                isFinished: false,
                matches: matches
            }
            window.addEventListener = jest.fn();
            const instance = shallow(<Grid {...props} />).instance();
            instance.handleResize = jest.fn();
            // Act
            instance.componentDidMount();

            // Assert
            expect(instance.state.start).toEqual(5);
            expect(instance.state.end).toEqual(showItems + 5);
            expect(window.addEventListener).toHaveBeenCalledWith('resize', instance.handleResize);
        });

        it('init state correctly when season did not finish and last post-match is not in last page index', () => {
            // Arrange
            const initWindowWidth = 500;
            window.innerWidth = initWindowWidth;
            const showItems = Math.ceil(initWindowWidth / 260);
            const start = 1;
            const matches = [
                { matchId: 1, status: 50 }, { matchId: 2, status: 50 }, { matchId: 3, status: 50 },
                { matchId: 4, status: 50 }, { matchId: 2, status: 0 }, { matchId: 3, status: 0 },
                { matchId: 7, status: 0 }, { matchId: 8, status: 0 }, { matchId: 9, status: 0 }
            ];
            const props = {
                ...defaultProps,
                isFinished: false,
                matches: matches
            }
            window.addEventListener = jest.fn();
            const instance = shallow(<Grid {...props} />).instance();
            instance.handleResize = jest.fn();
            // Act
            instance.componentDidMount();

            // Assert
            expect(instance.state.start).toEqual(start);
            expect(instance.state.end).toEqual(showItems + start );
            expect(window.addEventListener).toHaveBeenCalledWith('resize', instance.handleResize);
        });
    });

    describe('componentWillUnmount', () => {
        it('calls correctly functions', () => {
            // Arrange
            window.removeEventListener = jest.fn();

            const container = shallow(<Grid {...defaultProps} />);
            const instance = container.instance();

            // Act
            instance.componentWillUnmount();

            // Assert
            expect(window.removeEventListener).toHaveBeenCalledWith('resize', instance.handleResize);
        });
    });

    describe('handleResize', () => {
        it('updates correctly state when isScroll = false and window.innerWidth < 1024', () => {
            // Arrange
            window.innerWidth = 767;
            const container = shallow(<Grid {...defaultProps} />);
            const instance = container.instance();
            instance.setState({ isScroll: false });

            // Act
            instance.handleResize();

            // Assert
            expect(instance.state.isScroll).toEqual(true);
        });

        it('updates correctly state when isScroll = false and window.innerWidth = 1024', () => {
            // Arrange
            window.innerWidth = 1024;
            const container = shallow(<Grid {...defaultProps} />);
            const instance = container.instance();
            instance.setState({ isScroll: false });

            // Act
            instance.handleResize();

            // Assert
            expect(instance.state.isScroll).toEqual(true);
        });

        it('do nothing when isScroll = true and window.innerWidth < 1024', () => {
            // Arrange
            window.innerWidth = 767;
            const container = shallow(<Grid {...defaultProps} />);
            const instance = container.instance();
            instance.setState({ isScroll: true });
            instance.setState = jest.fn();

            // Act
            instance.handleResize();

            // Assert
            expect(instance.setState).toHaveBeenCalledTimes(0);
            expect(instance.state.isScroll).toEqual(true);
        });

        it('do nothing when isScroll = true and window.innerWidth = 1024', () => {
            // Arrange
            window.innerWidth = 1024;
            const container = shallow(<Grid {...defaultProps} />);
            const instance = container.instance();
            instance.setState({ isScroll: true });
            instance.setState = jest.fn();

            // Act
            instance.handleResize();

            // Assert
            expect(instance.setState).toHaveBeenCalledTimes(0);
            expect(instance.state.isScroll).toEqual(true);
        });

        it('updates correctly state when isMobile = true and window.innerWidth > 1024', () => {
            // Arrange
            window.innerWidth = 1025;
            const container = shallow(<Grid {...defaultProps} />);
            const instance = container.instance();
            instance.setState({ isScroll: true });

            // Act
            instance.handleResize();

            // Assert
            expect(instance.state.isScroll).toEqual(false);
        });
    });

    describe('onPrev', () => {
        it('setState correctly when start is zero', () => {
            // Arrange
            const container = shallow(<Grid {...defaultProps} />);
            const instance = container.instance();
            instance.setState({ start: 0, end: 10 });

            // Act
            instance.onPrev();

            // Assert
            expect(instance.state.start).toEqual(0);
            expect(instance.state.end).toEqual(10);
        });

        it('setState correctly when start from first item', () => {
            // Arrange
            const container = shallow(<Grid {...defaultProps} />);
            const instance = container.instance();
            instance.setState({ start: 1, end: 10 });

            // Act
            instance.onPrev();

            // Assert
            expect(instance.state.start).toEqual(0);
            expect(instance.state.end).toEqual(9);
        });
    });

    describe('onNext', () => {
        it('setState  correctly when end is match length', () => {
            // Arrange
            const props = {
                ...defaultProps,
                matches: [{matchId: 1}, {matchId: 2}]
            }
            const container = shallow(<Grid {...props} />);
            const instance = container.instance();
            instance.setState({ start: 1, end: 3 });

            // Act
            instance.onNext();

            // Assert
            expect(instance.state.start).toEqual(1);
            expect(instance.state.end).toEqual(3);
        });

        it('setState  correctly when end less than match length', () => {
            // Arrange
            const props = {
                ...defaultProps,
                matches: [{matchId: 1}, {matchId: 2}]
            }
            const container = shallow(<Grid {...props} />);
            const instance = container.instance();
            instance.setState({ start: 1, end: 2 });

            // Act
            instance.onNext();

            // Assert
            expect(instance.state.start).toEqual(2);
            expect(instance.state.end).toEqual(3);
        });
    });

    describe('getGridMatches', () => {
        it('return correctly data when isScroll = true', () => {
             // Arrange
             const props = {
                ...defaultProps,
                matches: [{matchId: 1}, {matchId: 2}]
            }
            const container = shallow(<Grid {...props} />);
            const instance = container.instance();
            instance.setState({ start: 1, end: 2, isScroll: true });

            // Act
            const result = instance.getGridMatches();

            // Assert
            expect(result).toEqual(props.matches);
        });

        it('return correctly data when isScroll = false', () => {
            // Arrange
            const props = {
               ...defaultProps,
               matches: [{matchId: 1}, {matchId: 2}]
           }
           const container = shallow(<Grid {...props} />);
           const instance = container.instance();
           instance.setState({ start: 0, end: 1, isScroll: false });

           // Act
           const result = instance.getGridMatches();

           // Assert
           expect(result).toEqual([{matchId: 1}]);
       });
    });

    describe('renders', () => {
        it('render correctly', () => {
            // Arrange
            const props = {
                ...defaultProps,
                matches: [{matchId: 1}, {matchId: 2}]
            }
            const container = shallow(<Grid {...props} />);
            const instance = container.instance();
            instance.setState({ start: 0, end: 1, isScroll: true });

            // Act
            const result = instance.render();

            // Assert
            expect(result).toMatchSnapshot();
        });
    })
});