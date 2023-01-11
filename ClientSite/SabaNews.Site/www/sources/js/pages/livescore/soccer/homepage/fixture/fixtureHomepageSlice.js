import { createSlice } from '@reduxjs/toolkit';

const fixtureHomepageSlice = createSlice({
    name: 'fixtureHomepageSlice',
    initialState: {
        leagues: null,
        liveMatches: null,
        league: null
    },
    reducers: {
        setLeagues: (state, action) => {
            state.leagues = action.payload;
        },
        setLeague: (state, action) => {
            state.league = action.payload;
        },
        setLiveMatches: (state, action) => {
            const liveMatches = action.payload,
            totalItems = liveMatches.length;
            for (let i = 0; i < totalItems; i++) {
                let index = state.matches.findIndex(item => item.matchId === liveMatches[i].matchId);
                if (index >= 0) {
                    state.matches[index] = liveMatches[i];
                }
            }
        }
    }
});

const { actions, reducer } = fixtureHomepageSlice;

export const {
    setLeagues,
    setLeague,
    setLiveMatches
} = actions;

export default reducer;