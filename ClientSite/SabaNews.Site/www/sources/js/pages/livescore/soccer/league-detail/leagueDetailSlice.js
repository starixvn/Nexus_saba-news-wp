import { createSlice } from '@reduxjs/toolkit';

const leagueDetailSlice = createSlice({
    name: 'leagueDetailSlice',
    initialState: {
        searchKey: '',
        majorLeagues: [],
        matches: null
    },
    reducers: {
        setSearchKey: (state, action) => {
            state.searchKey = action.payload;
        },
        setMajorLeagues: (state, action) => {
            state.majorLeagues = action.payload;
        },
        setMatches: (state, action) => {
            state.matches = action.payload;
        }
    }
});

const { actions, reducer } = leagueDetailSlice;

export const {
    setSearchKey,
    setMajorLeagues,
    setMatches
} = actions;

export default reducer;