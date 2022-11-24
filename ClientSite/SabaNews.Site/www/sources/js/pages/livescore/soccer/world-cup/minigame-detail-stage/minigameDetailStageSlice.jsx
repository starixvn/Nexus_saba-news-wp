import { WorldCup } from '@jsroot/common/constants';
import { createSlice } from '@reduxjs/toolkit';
const minigameStageSlice = createSlice({
    name: 'minigameDetailStageSlice',
    initialState: {
        matches: null,
        updateVoteResult: null,
        votes: null,
        league: null,
        stages: null,
        teams: null
    },
    reducers: {
        setVotes: (state, action) => {
            state.votes = action.payload;
        },
        setLeague: (state, action) => {
            const league = action.payload;
            state.league = league;
        },
        setMatches: (state, action) => {
            const totalItems = matches.length,
                matches = action.payload,
                stages = [];
            const groups = matches.filter(item => item.stage?.stageId === WorldCup.GroupsStage);
            let groupTeams = groups.map(item => item.homeTeam.teamId);
            groupTeams = [...new Set(groupTeams)];
            for (let i = 0; i < totalItems; i++) {
                const match = matches[i];
                if (match.stage && stages.findIndex(item => item.stageId === match.stage.stageId) === -1) {
                    stages.push(match.stage);
                }
            }
            state.matches = matches;
            state.teams = groupTeams;
            state.stages = stages;
        },
        setUpdateVote: (state, action) => {
            state.updateVoteResult = action.payload;
        }
    }
});

const { actions, reducer } = minigameStageSlice;

export const {
    setVotes,
    setLeague,
    setMatches,
    setUpdateVote
} = actions;

export default reducer;