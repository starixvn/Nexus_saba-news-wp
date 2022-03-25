import { createSlice } from '@reduxjs/toolkit';

const soccerMatchSlice = createSlice({
    name: 'soccerMatchSlice',
    initialState: {
        searchKey: '',
        matches: []
    },
    reducers: {
        setSearchKey: (state, action) => {
            state.searchKey = action.payload;
        },
        setMatches: (state, action) => {
            state.matches = action.payload;
        }
    }
});

const { actions, reducer } = soccerMatchSlice;

export const {
    setSearchKey,
    setMatches
} = actions;

export default reducer;