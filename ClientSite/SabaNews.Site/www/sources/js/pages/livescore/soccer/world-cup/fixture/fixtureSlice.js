import { createSlice } from '@reduxjs/toolkit';

const fixtureSlice = createSlice({
    name: 'fixtureSlice',
    initialState: {
        matches: null,
        liveMatches: null,
        league: null
    },
    reducers: {
        setMatches: (state, action) => {
            state.matches = action.payload;
        },
        setLeague: (state, action) => {
            state.league = action.payload;
        }
    }
});

const { actions, reducer } = fixtureSlice;

export const {
    setMatches,
    setLeague
} = actions;

export default reducer;