import { Euro } from '../euro-constants';
import DataService from '@jsroot/common/dataService';
import Site from '@jsroot/common/site';
import {
    setMatches,
    setLeague,
    setVotes
} from './minigameDetailSlice';

const getMatches = () => async dispatch => {
    const url = Site.resolveClientUrl('Euro2024/GetFixture');
    await DataService.postDataAsync(url).then(response => {
        const { league, matches } = response.data;
        dispatch(setLeague(league));
        dispatch(setMatches(matches));
    }).catch(Site.handleAxiosError);
};

const getVotes = () => async dispatch => {
    var params = {
        leagueId: Euro.leagueId,
        seasonId: Euro.seasonId
    }
    const url = Site.resolveClientUrl('Euro2024/GetMinigameByUser');
    await DataService.postDataAsync(url, params).then(response => {
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