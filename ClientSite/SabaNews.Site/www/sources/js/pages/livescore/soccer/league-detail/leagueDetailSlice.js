import { createSlice } from '@reduxjs/toolkit';
import { MatchStatus } from '@jsroot/common/constants';

const leagueDetailSlice = createSlice({
    name: 'leagueDetailSlice',
    initialState: {
        majorLeagues: [],
        league: null,
        stages: [],
        preMatches: null,
        postMatches: null,
        seasonStandings: []
    },
    reducers: {
        setMajorLeagues: (state, action) => {
            state.majorLeagues = action.payload;
        },
        setLeague: (state, action) => {
            state.league = action.payload;
        },
        setMatches: (state, action) => {
            const matches = action.payload;

            const helper = {};
            state.stages = matches.reduce((previousItem, currentItem) => {
                const key = currentItem.stage ? currentItem.stage.stageId : 0;
                if (key > 0 && !helper[key]) {
                    helper[key] = currentItem.stage;
                    previousItem.push(helper[key]);
                }

                return previousItem;
            }, []);

            state.preMatches = matches.filter(match => match.status !== MatchStatus.Finished);
            state.postMatches = matches.filter(match => match.status === MatchStatus.Finished);
        },
        setLiveMatches: (state, action) => {
            const liveMatches = action.payload;
            const totalItems = liveMatches.length;
            for (let i = 0; i < totalItems; i++) {
                let index = state.preMatches.findIndex(item => item.matchId === liveMatches[i].matchId);
                if (index >= 0) {
                    state.preMatches[index] = liveMatches[i];
                }

                index = state.postMatches.findIndex(item => item.matchId === liveMatches[i].matchId);
                if (index >= 0) {
                    state.postMatches[index] = liveMatches[i];
                }
            }
        },
        setSeasonStandings: (state, action) => {
            state.seasonStandings = action.payload;
        }
    }
});

const { actions, reducer } = leagueDetailSlice;

export const {
    setMajorLeagues,
    setLeague,
    setMatches,
    setLiveMatches,
    setSeasonStandings
} = actions;

export default reducer;