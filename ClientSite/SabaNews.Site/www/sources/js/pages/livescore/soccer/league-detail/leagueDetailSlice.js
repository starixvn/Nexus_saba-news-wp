import { createSlice } from '@reduxjs/toolkit';

const leagueDetailSlice = createSlice({
    name: 'leagueDetailSlice',
    initialState: {
        searchKey: '',
        majorLeagues: [],
        league: null,
        matches: null
    },
    reducers: {
        setSearchKey: (state, action) => {
            state.searchKey = action.payload;
        },
        setMajorLeagues: (state, action) => {
            state.majorLeagues = action.payload;
        },
        setLeague: (state, action) => {
            state.league = action.payload;
        },
        setMatches: (state, action) => {
            state.matches = action.payload;
        },
        setLiveMatches: (state, action) => {
            const liveMatches = action.payload;
            const totalItems = liveMatches.length;
            for (let i = 0; i < totalItems; i++) {
                const index = state.matches.findIndex(item => item.matchId === liveMatches[i].matchId);
                if (index >= 0) {
                    state.matches[index] = liveMatches[i];
                }
            }
        }
    }
});

const { actions, reducer } = leagueDetailSlice;

export const {
    setSearchKey,
    setMajorLeagues,
    setLeague,
    setMatches,
    setLiveMatches
} = actions;

export default reducer;