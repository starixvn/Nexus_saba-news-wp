import Site from '@jsroot/common/site';
import DataService from '@jsroot/common/dataService';
import {
    setMatch,
    setMajorLeagues,
    setActiveTab
} from './soccerMatchDetailSlice';

const getMatchDetail = () => async dispatch => {
    let params = {
        matchId: window.selectedMatchId
    };
    const url = Site.resolveClientUrl('soccer/GetMatchDetail');
    await DataService.postDataAsync(url, params).then(response => {
        dispatch(setMatch(response.data));
    }).catch(Site.handleAxiosError);
};


const getMajorLeagues = () => async dispatch => {
    const url = Site.resolveClientUrl('soccer/getMajorLeagues');
    await DataService.postDataAsync(url).then(response => {
        dispatch(setMajorLeagues(response.data));
    }).catch(Site.handleAxiosError);
};

export const ActionCreators = {
    setMatch,
    setMajorLeagues,
    getMatchDetail,
    getMajorLeagues,
    setActiveTab
};