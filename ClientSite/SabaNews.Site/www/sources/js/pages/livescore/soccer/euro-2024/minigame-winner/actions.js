import Site from '@jsroot/common/site';
import DataService from '@jsroot/common/dataService';
import { Euro } from '../euro-constants';

import {
    setMatches,
    setLeague,
    setVotes
} from './minigameWinnersSlice';

const getMatches = () => async dispatch => {
    const url = Site.resolveClientUrl('Euro2024/GetFixture');
    await DataService.postDataAsync(url).then(response => {
        const { league, matches } = response.data;
        dispatch(setLeague(league));
        dispatch(setMatches(matches));
    }).catch(Site.handleAxiosError);
};

const getVotes = stage => async dispatch => {
    const url = Site.resolveClientUrl('Euro2024/GetMinigameByStage');
    var param = {
        stage: stage,
        leagueId: Euro.leagueId,
        seasonId: Euro.seasonId
    };
    await DataService.postDataAsync(url, param).then(response => {
        window.stage = stage;
        dispatch(setVotes(response.data));
    }).catch(Site.handleAxiosError);
};

export const ActionCreators = {
    setMatches,
    setVotes,
    setLeague,
    getMatches,
    getVotes,
};