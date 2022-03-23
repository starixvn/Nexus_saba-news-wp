import { createSlice } from '@reduxjs/toolkit';

const soccerMatchSlice = createSlice({
    name: 'soccerMatchSlice',
    initialState: {
        matches: []
    },
    reducers: {
        setMatches: (state, action) => {
            state.matches = action.payload;
        }
    }
});

const { actions, reducer } = soccerMatchSlice;

export const {
    setMatches
} = actions;

export default reducer;