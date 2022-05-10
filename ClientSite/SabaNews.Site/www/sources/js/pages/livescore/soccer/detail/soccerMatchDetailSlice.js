import { createSlice } from '@reduxjs/toolkit';

const soccerMatchDetailSlice = createSlice({
    name: 'soccerMatchDetailSlice',
    initialState: {
        match: null,
        isLiveMatch: false,
        majorLeagues: [],
        activeTab: '',
    },
    reducers: {
        setMajorLeagues: (state, action) => {
            state.majorLeagues = action.payload;
        },
        setMatch: (state, action) => {
            state.match = action.payload;
        },
        setActiveTab: (state, action) => {
            state.activeTab =  action.payload;
        }
    }
});

const { actions, reducer } = soccerMatchDetailSlice;

export const {
    setMajorLeagues,
    setMatch,
    setActiveTab
} = actions;

export default reducer;