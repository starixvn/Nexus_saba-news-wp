import { createSlice } from '@reduxjs/toolkit';
import moment from 'moment';
import DateTimeService from '@jsroot/common/dateTimeService';
import { MatchStatus } from '@jsroot/common/constants';

const leagueDetailSlice = createSlice({
    name: 'leagueDetailSlice',
    initialState: {
        majorLeagues: [],
        league: null,
        stages: [],
        preMatches: null,
        postMatches: null,
        seasonStandings: []
    },
    reducers: {
        setMajorLeagues: (state, action) => {
            state.majorLeagues = action.payload;
        },
        setLeague: (state, action) => {
            state.league = action.payload;
        },
        setMatches: (state, action) => {
            const matches = action.payload,
                totalItems = matches.length,
                stages = [],
                preMatches = [],
                postMatches = [];
            for (let i = 0; i < totalItems; i++) {
                const match = matches[i];
                const today = moment(DateTimeService.getCurrentClientDateTime()).format('YYYY-MM-DD');
                const startTime = moment(DateTimeService.convertFromUTC(match.startTime)).format('YYYY-MM-DD');
                const endTime = moment(DateTimeService.convertFromUTC(match.endTime)).format('YYYY-MM-DD');
                if (match.stage && stages.findIndex(item => item.stageId === match.stage.stageId) === -1) {
                    stages.push(match.stage);
                }

                if (match.status !== MatchStatus.Finished
                    && match.status !== MatchStatus.Cancel
                    && (moment(startTime).isSameOrAfter(today) || moment(endTime).isSameOrAfter(today))) {
                    preMatches.push(match);
                }

                if (match.status === MatchStatus.Finished) {
                    postMatches.push(match);
                }
            }

            state.stages = stages;
            state.preMatches = preMatches;
            state.postMatches = postMatches;
        },
        setLiveMatches: (state, action) => {
            const liveMatches = action.payload;
            const totalItems = liveMatches.length;
            for (let i = 0; i < totalItems; i++) {
                let index = state.preMatches.findIndex(item => item.matchId === liveMatches[i].matchId);
                if (index >= 0) {
                    state.preMatches[index] = liveMatches[i];
                }

                index = state.postMatches.findIndex(item => item.matchId === liveMatches[i].matchId);
                if (index >= 0) {
                    state.postMatches[index] = liveMatches[i];
                }
            }
        },
        setSeasonStandings: (state, action) => {
            state.seasonStandings = action.payload;
        }
    }
});

const { actions, reducer } = leagueDetailSlice;

export const {
    setMajorLeagues,
    setLeague,
    setMatches,
    setLiveMatches,
    setSeasonStandings
} = actions;

export default reducer;