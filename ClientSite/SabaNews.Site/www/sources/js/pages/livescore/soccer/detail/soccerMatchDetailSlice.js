import { createSlice } from '@reduxjs/toolkit';

const soccerMatchDetailSlice = createSlice({
    name: 'soccerMatchDetailSlice',
    initialState: {
        soccerMatch: null,
        lineup: null,
        isLiveMatch: false
    },
    reducers: {
        setSoccerMatch: (state, action) => {
            state.soccerMatch = action.payload;
        },
        setLineup: (state, action) => {
            state.lineup = action.payload;
        },
        setIsLiveMatch: (state, action) => {
            state.isLiveMatch = action.payload;
        }
    }
});

const { actions, reducer } = soccerMatchDetailSlice;

export const {
    setSoccerMatch,
    setLineup,
    setIsLiveMatch
} = actions;

export default reducer;