import Site from '@jsroot/common/site';
import DataService from '@jsroot/common/dataService';
import {
    setSoccerMatch,
    setLineup,
    setIsLiveMatch,
    setloadTabStatus
} from './soccerMatchDetailSlice';

const getSoccerMatchDetail = () => async dispatch => {
    let params = {
        matchId: window.selectedMatchId
    };
    const url = Site.resolveClientUrl('soccer/GetSoccerMatchDetail');
    await DataService.postDataAsync(url, params).then(response => {
        dispatch(setSoccerMatch(response.data));
    }).catch(Site.handleAxiosError);//.then(() => Site.unblockUI());
};

const getLineupDetail = () => async dispatch => {
    let params = {
        matchId: window.selectedMatchId
    };
    const url = Site.resolveClientUrl('soccer/GetLineupDetail');
    await DataService.postDataAsync(url, params).then(response => {
        dispatch(setLineup(response.data));
    }).catch(Site.handleAxiosError);//.then(() => Site.unblockUI());
};

export const ActionCreators = {
    setSoccerMatch,
    setLineup,
    setIsLiveMatch,
    setloadTabStatus,
    getSoccerMatchDetail,
    getLineupDetail
};