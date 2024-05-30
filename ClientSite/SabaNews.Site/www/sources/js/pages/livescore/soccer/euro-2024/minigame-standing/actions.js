import Site from '@jsroot/common/site';
import DataService from '@jsroot/common/dataService';

import {
    setMatches,
    setLeague,
    setVotes
} from './minigameStandingSlice';

const getMatches = () => async dispatch => {
    const url = Site.resolveClientUrl('Euro2024/GetFixture');
    await DataService.postDataAsync(url).then(response => {
        const { league, matches } = response.data;
        dispatch(setLeague(league));
        dispatch(setMatches(matches));
    }).catch(Site.handleAxiosError);
};

const getVotes = stage => async dispatch => {
    window.stage = stage;
    const url = Site.resolveClientUrl('Euro2024/GetMinigameByStage');
    var param = {
        stage: stage
    };
    await DataService.postDataAsync(url, param).then(response => {
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