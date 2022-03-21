import { createSlice } from '@reduxjs/toolkit';

const soccerMatchDetailSlice = createSlice({
    name: 'soccerMatchDetailSlice',
    initialState: {
        soccerMatch: null
    },
    reducers: {
        setSoccerMatch: (state, action) => {
            state.soccerMatch = action.payload;
        }
    }
});

const { actions, reducer } = soccerMatchDetailSlice;

export const {
    setSoccerMatch
} = actions;

export default reducer;