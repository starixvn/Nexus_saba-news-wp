import React from 'react';
import { shallow } from 'enzyme';
import {Item} from '../Item';

describe('Item', () => {
    beforeAll(() => {
        const appContainer = document.createElement('div');
        appContainer.setAttribute('class', 'detailWinners');
        document.body.appendChild(appContainer);

        const appContainer2 = document.createElement('div');
        appContainer2.setAttribute('class', 'detailWinners');
        document.body.appendChild(appContainer2);
    });

    afterAll(() => {
        const appContainer = document.getElementsByClassName('detailWinners');
        if (appContainer) {
            document.body.removeChild(appContainer);
        }
        jest.clearAllMocks();
    });

    describe('handleClick 1 row', () => {
        it('return correctly when call function', () => {
            // Arrange           
            const defaultProps = {
                Winner: {userName: 'test', icon: '', point: 0, createdDate: "", updatedDate: null},
                number: 0,
                winnerLength: 1
            };
            const container = shallow(<Item {...defaultProps} />);
            const instance = container.instance();

            // Act
            instance.handleClick(defaultProps.winnerLength, defaultProps.number);

            // Assert 
            expect(document.getElementsByClassName('detailWinners')[0].style.display).toEqual("");

        });
    });

    describe('handleClick 2 row', () => {
        it('return correctly when call function', () => {
            // Arrange           
            const defaultProps = {
                Winner: {userName: 'test', icon: '', point: 0, createdDate: "", updatedDate: null},
                number: 0,
                winnerLength: 2
            };
            const container = shallow(<Item {...defaultProps} />);
            const instance = container.instance();

            // Act
            instance.handleClick(defaultProps.winnerLength, defaultProps.number);

            // Assert 
            expect(document.getElementsByClassName('detailWinners')[0].style.display).toEqual("");
            expect(document.getElementsByClassName('detailWinners')[1].style.display).toEqual("");

        });
    });

    describe('show detail stage 4', () => {
        it('return correctly when call function', () => {
            // Arrange           
            const defaultProps = {
                Winner: {userName: 'test', icon: '', point: 0, createdDate: "", updatedDate: null,
                    detail:{
                        pointThirdPlace: 0,
                        pointEightTeamStage: 0,
                        pointSemifinalsStage: 0,
                        pointQuarterfinalsStage: 0,
                        pointFinalStage: 0
                }},
                number: 0,
                winnerLength: 2
            };
            window.stage = 4;
            const container = shallow(<Item {...defaultProps} />);
            const instance = container.instance();

            // Act
            instance.handleClick(defaultProps.winnerLength, defaultProps.number);

            // Assert 
            expect(document.getElementsByClassName('detailWinners')[0].style.display).toEqual("");
            expect(document.getElementsByClassName('detailWinners')[1].style.display).toEqual("");

        });
    });
});