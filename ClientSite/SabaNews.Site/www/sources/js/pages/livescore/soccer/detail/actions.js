import Site from '@jsroot/common/site';
import DataService from '@jsroot/common/dataService';
import {
    setSoccerMatch
} from './soccerMatchDetailSlice';

const getSoccerMatchDetail = () => async dispatch => {
    //Site.blockUI();
    const url = Site.resolveClientUrl('soccer/getSoccerMatchDetail');
    await DataService.postDataAsync(url).then(response => {
        dispatch(setSoccerMatch(response.data));
    }).catch(Site.handleAxiosError);//.then(() => Site.unblockUI());
};

export const ActionCreators = {
    setSoccerMatch,
    getSoccerMatchDetail
};