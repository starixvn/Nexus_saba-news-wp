import { createSlice } from '@reduxjs/toolkit';

const soccerMatchSlice = createSlice({
    name: 'soccerMatchSlice',
    initialState: {
        searchKey: '',
        majorLeagues: [],
        matches: []
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

const { actions, reducer } = soccerMatchSlice;

export const {
    setSearchKey,
    setMajorLeagues,
    setMatches
} = actions;

export default reducer;