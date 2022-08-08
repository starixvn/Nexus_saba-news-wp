import { createSlice } from '@reduxjs/toolkit';

const fixtureSlice = createSlice({
    name: 'fixtureSlice',
    initialState: {
        matches: null,
        liveMatches: null,
        league: null
    },
});

const { actions, reducer } = fixtureSlice;

export const {
} = actions;

export default reducer;