import { createSlice } from '@reduxjs/toolkit';
import DateTimeService from '@jsroot/common/dateTimeService';

const selectedDate = window.selectedEventDate ? window.selectedEventDate : DateTimeService.getCurrentClientDateTime();
const today = new Date(DateTimeService.getCurrentClientDateTime());
const minDate = new Date(2020, 0, 1);
const maxDate = new Date(today.getFullYear() + 1, 12, 0);

const soccerMatchSlice = createSlice({
    name: 'soccerMatchSlice',
    initialState: {
        isMobile: false,
        today: DateTimeService.formatDateTime(today, 'YYYY-MM-DD'),
        minDate: DateTimeService.formatDateTime(minDate, 'YYYY-MM-DD'),
        maxDate: DateTimeService.formatDateTime(maxDate, 'YYYY-MM-DD'),
        searchKey: '',
        selectedDate: DateTimeService.formatDateTime(selectedDate, 'YYYY-MM-DD'),
        dateRanges: [],
        majorLeagues: [],
        matches: null,
        favouriteMatches: []
    },
    reducers: {
        setIsMobile: (state, action) => {
            state.isMobile = action.payload;
        },
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
        },
        setLiveMatches: (state, action) => {
            const liveMatches = action.payload;
            if (state.today === state.selectedDate) {
                if (window.isLivePage) {
                    state.matches = liveMatches;
                } else {
                    const totalItems = liveMatches.length;
                    for (let i = 0; i < totalItems; i++) {
                        const index = state.matches.findIndex(item => item.matchId === liveMatches[i].matchId);
                        if (index >= 0) {
                            state.matches[index] = liveMatches[i];
                        }
                    }
                }
            }
        }
    }
});

const { actions, reducer } = soccerMatchSlice;

export const {
    setIsMobile,
    setSearchKey,
    setSelectedDate,
    setDateRanges,
    setMajorLeagues,
    setMatches,
    setLiveMatches
} = actions;

export default reducer;