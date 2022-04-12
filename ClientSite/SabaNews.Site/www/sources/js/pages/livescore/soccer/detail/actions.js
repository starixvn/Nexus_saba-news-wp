import Site from '@jsroot/common/site';
import DataService from '@jsroot/common/dataService';
import {
    setMatch,
    setIsLiveMatch,
    setloadTabStatus,
    setHeadToHead
} from './soccerMatchDetailSlice';

const getMatchDetail = () => async dispatch => {
    let params = {
        matchId: window.selectedMatchId
    };
    const url = Site.resolveClientUrl('soccer/GetMatchDetail');
    await DataService.postDataAsync(url, params).then(response => {
        dispatch(setMatch(response.data));
    }).catch(Site.handleAxiosError);//.then(() => Site.unblockUI());
};


const getHeadToHead = () => async dispatch => {
    let params = {
        matchId: window.selectedMatchId
    };
    const url = Site.resolveClientUrl('soccer/getHeadToHead');
    await DataService.postDataAsync(url, params).then(response => {
        dispatch(setHeadToHead(response.data));
    }).catch(Site.handleAxiosError);//.then(() => Site.unblockUI());
};

const updateData = () => async dispatch => {
    let params = {
        matchId: window.selectedMatchId
    };
    const url = Site.resolveClientUrl('soccer/UpdateDetailData');
    await DataService.postDataAsync(url, params).then(response => {
    }).catch(Site.handleAxiosError);//.then(() => Site.unblockUI());
};

export const ActionCreators = {
    setMatch,
    setIsLiveMatch,
    setloadTabStatus,
    setHeadToHead,
    getMatchDetail,
    getHeadToHead,
    updateData
};