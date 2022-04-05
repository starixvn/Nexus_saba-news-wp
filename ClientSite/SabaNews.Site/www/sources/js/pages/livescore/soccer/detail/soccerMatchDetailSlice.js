import { createSlice } from '@reduxjs/toolkit';

const soccerMatchDetailSlice = createSlice({
    name: 'soccerMatchDetailSlice',
    initialState: {
        soccerMatch: null,
        lineup: null,
        isLiveMatch: false,
        loadTabStatus: {
            enableTabMatchSummary: true,
            enableTabStatistics: true,
            enableTabLineUp: true
        }
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
        },
        setloadTabStatus: (state, action) => {
            state.loadTabStatus = action.payload;
        }
    }
});

const { actions, reducer } = soccerMatchDetailSlice;

export const {
    setSoccerMatch,
    setLineup,
    setIsLiveMatch,
    setloadTabStatus
} = actions;

export default reducer;