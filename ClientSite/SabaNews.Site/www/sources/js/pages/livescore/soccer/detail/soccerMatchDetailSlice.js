import { createSlice } from '@reduxjs/toolkit';

const soccerMatchDetailSlice = createSlice({
    name: 'soccerMatchDetailSlice',
    initialState: {
        match: null,
        isLiveMatch: false,
        favLeagues: [],
        activeTab: '',
    },
    reducers: {
        setFavLeagues: (state, action) => {
            state.favLeagues = action.payload;
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
    setFavLeagues,
    setMatch,
    setActiveTab
} = actions;

export default reducer;