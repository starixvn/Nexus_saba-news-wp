import configureMockStore from 'redux-mock-store';
import thunk from 'redux-thunk';
import Site from '@jsroot/common/site';
import DataService from '@jsroot/common/dataService';
import { ActionCreators } from '../actions';

const middlewares = [thunk];
const mockStore = configureMockStore(middlewares);

describe('actionCreators', () => {
    beforeAll(() => {
        Site.resolveClientUrl = jest.fn(url => url);
    });

    afterEach(() => {
        jest.clearAllMocks();
    });

    describe('getMajorLeagues', () => {
        it('should call setMajorLeagues when get major leagues have been done', async () => {
            // Arrange
            const response = {
                data: [{
                    leagueId: 1,
                    leagueNameEN: 'Champions League',
                    leagueNameVN: 'Champions League'
                },
                {
                    leagueId: 2,
                    leagueNameEN: 'Premier League',
                    leagueNameVN: 'Ngoại hạng Anh'
                }]
            };
            const promise = Promise.resolve(response);
            DataService.postDataAsync = jest.fn(() => promise);
            const expectedActions = [{ type: ActionCreators.setMajorLeagues.type, payload: response.data }];
            const store = mockStore({
                today: '',
                majorLeagues: []
            });

            // Act
            await store.dispatch(ActionCreators.getMajorLeagues());

            // Assert
            expect(DataService.postDataAsync).toHaveBeenCalledWith('soccer/getMajorLeagues');
            expect(store.getActions()).toEqual(expectedActions);
        });
    });

    describe('getMatchesByDate', () => {
        it('should call setMatches when get matches have been done', async () => {
            // Arrange
            const expectedParams = {
                eventDate: '2022-05-25'
            };
            const response = {
                data: [{
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
                }]
            };
            const promise = Promise.resolve(response);
            DataService.postDataAsync = jest.fn(() => promise);
            const expectedActions = [{ type: ActionCreators.setMatches.type, payload: response.data }];
            const store = mockStore({
                today: '',
                matches: []
            });

            // Act
            await store.dispatch(ActionCreators.getMatchesByDate(expectedParams));

            // Assert
            expect(DataService.postDataAsync).toHaveBeenCalledWith('soccer/getMatches', expectedParams);
            expect(store.getActions()).toEqual(expectedActions);
        });
    });

    describe('getLiveMatches', () => {
        it('should call setLiveMatches when get matches have been done', async () => {
            // Arrange
            const response = {
                data: [{
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
                }]
            };
            const promise = Promise.resolve(response);
            DataService.postDataAsync = jest.fn(() => promise);
            const expectedActions = [{ type: ActionCreators.setLiveMatches.type, payload: response.data }];
            const store = mockStore({
                today: '',
                matches: []
            });

            // Act
            await store.dispatch(ActionCreators.getLiveMatches());

            // Assert
            expect(DataService.postDataAsync).toHaveBeenCalledWith('soccer/getLiveMatches');
            expect(store.getActions()).toEqual(expectedActions);
        });
    });
});