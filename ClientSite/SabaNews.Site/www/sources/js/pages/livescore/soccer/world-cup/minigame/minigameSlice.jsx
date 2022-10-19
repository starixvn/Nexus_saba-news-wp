import { createSlice } from '@reduxjs/toolkit';

const minigameSlice = createSlice({
    name: 'minigameSlice',
    initialState: {
        players: null,
        matches: null
    },
    reducers: {
        setMatches: (state, action) => {
            const matches = action.payload,
                totalItems = matches.length,
                stages = [];
            for (let i = 0; i < totalItems; i++) {
                const match = matches[i];
                if (match.stage && stages.findIndex(item => item.stageId === match.stage.stageId) === -1) {
                    stages.push(match.stage);
                }
            }
            state.stages = stages;
            state.matches = matches;
        },
        setPlayers: (state, action) => {
            const players = action.payload;
            state.players = players;
        }
    }
});

const { actions, reducer } = minigameSlice;

export const {
    setMatches,
    setPlayers
} = actions;

export default reducer;