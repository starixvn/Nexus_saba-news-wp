import { createSlice } from '@reduxjs/toolkit';
import moment from 'moment';
import DateTimeService from '@jsroot/common/dateTimeService';
import { MatchStatus } from '@jsroot/common/constants';

const favouriteMatchSlice = createSlice({
    name: 'favouriteMatchSlice',
    initialState: {
        majorLeagues: [],
        matches: null,
        preMatches: null,
        postMatches: null,
        liveMatches: null
    },
    reducers: {
        setMajorLeagues: (state, action) => {
            state.majorLeagues = action.payload;
        },
        setMatches: (state, action) => {
            const data = action.payload;
            const postMatches = [];
            const preMatches = [];
            let liveMatches = [];
            const matchesTmp = data.matches;
            let currenLiveMatches = data.liveMatches;
            const matchIds = JSON.parse(localStorage.getItem("favourite-matches"));
            const listLiveFilter = matchIds?.join(',');
            if(matchIds) {
                liveMatches = currenLiveMatches.filter(item => listLiveFilter.includes(item.matchId.toString()));
            }
            const totalItem = data.matches.length;
            for (let i = 0; i < totalItem; i++) {
                const match = matchesTmp[i];
                const today = moment(DateTimeService.getCurrentClientDateTime()).format('YYYY-MM-DD');
                const startTime = moment(DateTimeService.convertFromUTC(match.startTime)).format('YYYY-MM-DD');
                const endTime = moment(DateTimeService.convertFromUTC(match.endTime)).format('YYYY-MM-DD');
                if (match.stage && stages.findIndex(item => item.stageId === match.stage.stageId) === -1) {
                    stages.push(match.stage);
                }

                if (match.status !== MatchStatus.Finished
                    && (moment(startTime).isSameOrAfter(today) || moment(endTime).isSameOrAfter(today))) {
                    //const liveIndex = liveMatches.findIndex(item => item.matchId === match.matchId);
                    //if(liveIndex === -1 ) {
                    preMatches.push(match);
                    //}
                }

                if (match.status === MatchStatus.Finished) {
                    postMatches.push(match);
                }
            }
            state.matches = data.matches;
            state.postMatches = postMatches;
            state.preMatches = preMatches;
            state.liveMatches = liveMatches;

        },
        setLiveMatches: (state, action) => {
            const liveMatches = action.payload;
            const totalItems = liveMatches.length;
            for (let i = 0; i < totalItems; i++) {
                let index = state.preMatches.findIndex(item => item.matchId === liveMatches[i].matchId);
                if (index >= 0) {
                    state.preMatches[index] = liveMatches[i];
                }

                index = state.matches.findIndex(item => item.matchId === liveMatches[i].matchId);
                if (index >= 0) {
                    state.matches[index] = liveMatches[i];
                }

                index = state.postMatches.findIndex(item => item.matchId === liveMatches[i].matchId);
                if (index >= 0) {
                    state.postMatches[index] = liveMatches[i];
                }

                index = state.liveMatches.findIndex(item => item.matchId === liveMatches[i].matchId);
                if (index >= 0) {
                    state.liveMatches[index] = liveMatches[i];
                }
            }
        }
    }
});

const { actions, reducer } = favouriteMatchSlice;

export const {
    setMajorLeagues,
    setMatches,
    setLiveMatches
} = actions;

export default reducer;