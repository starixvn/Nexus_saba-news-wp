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
                data: [
                    { leagueId: 1, leagueNameEN: 'Champions League', leagueNameVN: 'Champions League' },
                    { leagueId: 2, leagueNameEN: 'Premier League', leagueNameVN: 'Ngoại hạng Anh' }
                ]
            };
            const promise = Promise.resolve(response);
            DataService.postDataAsync = jest.fn(() => promise);
            const expectedActions = [{ type: ActionCreators.setMajorLeagues.type, payload: response.data }];
            const store = mockStore({
                seasonStandings: [],
                majorLeagues: []
            });

            // Act
            await store.dispatch(ActionCreators.getMajorLeagues());

            // Assert
            expect(DataService.postDataAsync).toHaveBeenCalledWith('soccer/getMajorLeagues');
            expect(store.getActions()).toEqual(expectedActions);
        });
    });

    describe('getMatchesBySeason', () => {
        it('should call setMatches when get matches have been done', async () => {
            // Arrange
            const expectedParams = {
                leagueId: 1,
                seasonId: 1
            };
            const response = {
                data: {
                    league: {
                        leagueId: 1,
                        leagueNameEN: 'Premier League',
                        leagueNameVN: 'Ngoại hạng Anh'
                    },
                    matches: [{
                        matchId: 1,
                        homeTeam: { teamId: 1, teamNameEN: 'Arsenal' },
                        awayTeam: { teamId: 2, teamNameEN: 'Everton' }
                    }]
                }
            };
            const promise = Promise.resolve(response);
            DataService.postDataAsync = jest.fn(() => promise);
            const expectedActions = [
                { type: ActionCreators.setLeague.type, payload: response.data.league },
                { type: ActionCreators.setMatches.type, payload: response.data.matches }
            ];
            const store = mockStore({
                majorLeagues: [],
                league: null,
                preMatches: null,
                postMatches: null
            });

            // Act
            await store.dispatch(ActionCreators.getMatchesBySeason(expectedParams));

            // Assert
            expect(DataService.postDataAsync).toHaveBeenCalledWith('soccer/getMatchesBySeason', expectedParams);
            expect(store.getActions()).toEqual(expectedActions);
        });
    });

    describe('getSeasonStandings', () => {
        it('should call setSeasonStandings when get season standings have been done', async () => {
            // Arrange
            const expectedParams = { seasonId: 1 };
            const response = {
                data: [
                    {
                        stageId: 1, stageNameEN: 'League',
                        teamId: 1, teamNameEN: 'Manchester City'
                    }
                ]
            };
            const promise = Promise.resolve(response);
            DataService.postDataAsync = jest.fn(() => promise);
            const expectedActions = [{ type: ActionCreators.setSeasonStandings.type, payload: response.data }];
            const store = mockStore({
                majorLeagues: [],
                seasonStandings: []
            });

            // Act
            await store.dispatch(ActionCreators.getSeasonStandings(expectedParams));

            // Assert
            expect(DataService.postDataAsync).toHaveBeenCalledWith('soccer/getSeasonStandings', expectedParams);
            expect(store.getActions()).toEqual(expectedActions);
        });
    });
});