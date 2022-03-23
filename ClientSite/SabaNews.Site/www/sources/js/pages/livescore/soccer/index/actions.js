import Site from '@jsroot/common/site';
import DataService from '@jsroot/common/dataService';
import {
    setMatches
} from './soccerMatchSlice';

const getMatches = (params) => async dispatch => {
    //Site.blockUI();
    const url = Site.resolveClientUrl('soccer/getMatches');
    await DataService.postDataAsync(url, params).then(response => {
        dispatch(setMatches(response.data));
    }).catch(Site.handleAxiosError);//.then(() => Site.unblockUI());
};

export const ActionCreators = {
    setMatches,
    getMatches
};