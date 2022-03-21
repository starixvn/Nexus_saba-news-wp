import { createSlice } from '@reduxjs/toolkit';

const soccerMatchSlice = createSlice({
    name: 'soccerMatchSlice',
    initialState: {
        soccerMatches: []
    },
    reducers: {
        setSoccerMatches: (state, action) => {
            state.soccerMatches = action.payload;
        },
        setLiveSoccerMatches: (state, action) => {
            const totalItems = action.payload.length;
            for (let i = 0; i < totalItems; i++) {
                const index = state.soccerMatches.findIndex(item => item.matchId === action.payload[i].matchId);
                if (index >= 0) {
                    state.soccerMatches[index] = action.payload[i];
                }
            }
        }
    }
});

const { actions, reducer } = soccerMatchSlice;

export const {
    setSoccerMatches,
    setLiveSoccerMatches
} = actions;

export default reducer;