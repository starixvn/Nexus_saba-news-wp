import { createSlice } from '@reduxjs/toolkit';

const soccerMatchDetailSlice = createSlice({
    name: 'soccerMatchDetailSlice',
    initialState: {
        match: null,
        headToHead: null,
        isLiveMatch: false
    },
    reducers: {
        setMatch: (state, action) => {
            state.match = action.payload;
        },
        setHeadToHead: (state, action) => {
            state.headToHead = action.payload;
        },
        setIsLiveMatch: (state, action) => {
            state.isLiveMatch = action.payload;
        }
    }
});

const { actions, reducer } = soccerMatchDetailSlice;

export const {
    setMatch,
    setHeadToHead,
    setIsLiveMatch,
} = actions;

export default reducer;