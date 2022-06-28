import { createSlice } from '@reduxjs/toolkit';

const favouriteLeagueSlice = createSlice({
    name: 'favouriteLeagueSlice',
    initialState: {
        favLeagues: null,
        leagues: null
    },
    reducers: {
        setFavLeagues: (state, action) => {
            state.favLeagues = action.payload;
        },
        setLeagues: (state, action) => {
            state.leagues = action.payload;
        }
    },
});

const { actions, reducer } = favouriteLeagueSlice;

export const {
    setFavLeagues,
    setLeagues
} = actions;

export default reducer;