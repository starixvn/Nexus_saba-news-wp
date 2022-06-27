import { createSlice } from '@reduxjs/toolkit';

const favouriteLeagueSlice = createSlice({
    name: 'favouriteLeagueSlice',
    initialState: {
        matches: null
    },
    reducers: {
        setMatches: (state, action) => {
            state.matches = action.payload;
        },
    },
});

const { actions, reducer } = favouriteLeagueSlice;

export const {
    setMatches
} = actions;

export default reducer;