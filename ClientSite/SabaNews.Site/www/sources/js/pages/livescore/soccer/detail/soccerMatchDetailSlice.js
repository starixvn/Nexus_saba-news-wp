import { createSlice } from '@reduxjs/toolkit';

const soccerMatchDetailSlice = createSlice({
    name: 'soccerMatchDetailSlice',
    initialState: {
        match: null,
        isLiveMatch: false,
        majorLeagues: [],
    },
    reducers: {
        setMajorLeagues: (state, action) => {
            state.majorLeagues = action.payload;
        },
        setMatch: (state, action) => {
            state.match = action.payload;
        }
    }
});

const { actions, reducer } = soccerMatchDetailSlice;

export const {
    setMajorLeagues,
    setMatch
} = actions;

export default reducer;