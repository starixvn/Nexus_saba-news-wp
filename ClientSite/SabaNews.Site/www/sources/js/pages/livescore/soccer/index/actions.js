import Site from '@jsroot/common/site';
import DataService from '@jsroot/common/dataService';
import {
    setSearchKey,
    setMajorLeagues,
    setMatches
} from './soccerMatchSlice';

const getMajorLeagues = () => async dispatch => {
    //Site.blockUI();
    const url = Site.resolveClientUrl('soccer/getMajorLeagues');
    await DataService.postDataAsync(url).then(response => {
        dispatch(setMajorLeagues(response.data));
    }).catch(Site.handleAxiosError);//.then(() => Site.unblockUI());
};

const getMatches = params => async dispatch => {
    //Site.blockUI();
    const url = Site.resolveClientUrl('soccer/getMatches');
    await DataService.postDataAsync(url, params).then(response => {
        dispatch(setMatches(response.data));
    }).catch(Site.handleAxiosError);//.then(() => Site.unblockUI());
};

export const ActionCreators = {
    setSearchKey,
    setMajorLeagues,
    setMatches,
    getMajorLeagues,
    getMatches
};