import { createSlice } from '@reduxjs/toolkit';

const matchDetailShortcutSlice = createSlice({
    name: 'matchDetailShortcutSlice',
    initialState: {
        match: null
    },
    reducers: {
        setMatch: (state, action) => {
            state.match = action.payload;
        }
    }
});

const { actions, reducer } = matchDetailShortcutSlice;

export const {
    setMatch,
} = actions;

export default reducer;