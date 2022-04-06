import { createSlice } from '@reduxjs/toolkit';
import DateTimeService from '@jsroot/common/dateTimeService';

const selectedDate = window.selectedEventDate ? window.selectedEventDate : DateTimeService.getCurrentClientDateTime();
const today = new Date(DateTimeService.getCurrentClientDateTime());
const minDate = new Date(2020, 0, 1);
const maxDate = new Date(today.getFullYear() + 1, 12, 0);

const soccerMatchSlice = createSlice({
    name: 'soccerMatchSlice',
    initialState: {
        today: DateTimeService.formatDateTime(today, 'YYYY-MM-DD'),
        minDate: DateTimeService.formatDateTime(minDate, 'YYYY-MM-DD'),
        maxDate: DateTimeService.formatDateTime(maxDate, 'YYYY-MM-DD'),
        searchKey: '',
        selectedDate: DateTimeService.formatDateTime(selectedDate, 'YYYY-MM-DD'),
        dateRanges: [],
        majorLeagues: [],
        matches: null
    },
    reducers: {
        setSearchKey: (state, action) => {
            state.searchKey = action.payload;
        },
        setSelectedDate: (state, action) => {
            state.selectedDate = action.payload;
        },
        setDateRanges: (state, action) => {
            state.dateRanges = action.payload;
        },
        setMajorLeagues: (state, action) => {
            state.majorLeagues = action.payload;
        },
        setMatches: (state, action) => {
            state.matches = action.payload;
        }
    }
});

const { actions, reducer } = soccerMatchSlice;

export const {
    setSearchKey,
    setSelectedDate,
    setDateRanges,
    setMajorLeagues,
    setMatches
} = actions;

export default reducer;