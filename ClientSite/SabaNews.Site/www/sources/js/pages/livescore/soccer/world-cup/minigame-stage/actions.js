import Site from '@jsroot/common/site';
import { WorldCup } from '@jsroot/common/constants';
import DataService from '@jsroot/common/dataService';
import {
    setMatches,
    setLeague,
    setVotes
} from './minigameStageSlice';

const getMatches = () => async dispatch => {
    console.log(1);
    const url = Site.resolveClientUrl('soccer/getMatchesBySeason');
    var param = {
        leagueId: WorldCup.leagueId,
        seasonId: WorldCup.seasonId
    }
    await DataService.postDataAsync(url, param).then(response => {
        const { league, matches } = response.data;
        dispatch(setLeague(league));
        dispatch(setMatches(matches));
    }).catch(Site.handleAxiosError);
};

const getVotes = () => async dispatch => {
    const url = Site.resolveClientUrl('worldcup2022/GetMinigameByUser');
    var param = {
        stage: window.stage
    };
    await DataService.postDataAsync(url, param).then(response => {
        dispatch(setMinigame(response.data));
    }).catch(Site.handleAxiosError);
};


export const ActionCreators = {
    setMatches,
    setVotes,
    setLeague,
    getMatches,
    getVotes
};