import { createSlice } from '@reduxjs/toolkit';

const teamDetailSlice = createSlice({
    name: 'teamDetailSlice',
    initialState: {
        matches: null,
        players: null,
        team: null
    },
    reducers: {
        setMatches: (state, action) => {
            state.matches = action.payload;
        },
        setPlayers: (state, action) => {
            state.players = action.payload;
        },
        setTeam: (state, action) => {
            state.team = action.payload;
        }
    }
});

const { actions, reducer } = teamDetailSlice;

export const {
    setMatches,
    setPlayers,
    setTeam
} = actions;

export default reducer;