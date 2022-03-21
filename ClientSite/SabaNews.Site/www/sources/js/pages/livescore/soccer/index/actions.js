import Site from '@jsroot/common/site';
import DataService from '@jsroot/common/dataService';
import {
    setSoccerMatches,
    setLiveSoccerMatches
} from './soccerMatchSlice';

const getSoccerMatches = () => async dispatch => {
    //Site.blockUI();
    const url = Site.resolveClientUrl('soccer/getSoccerMatches');
    await DataService.postDataAsync(url).then(response => {
        dispatch(setSoccerMatches(response.data));
    }).catch(Site.handleAxiosError);//.then(() => Site.unblockUI());
};

const getLiveSoccerMatches = () => async dispatch => {
    //Site.blockUI();
    const url = Site.resolveClientUrl('soccer/getLiveSoccerMatches');
    await DataService.postDataAsync(url).then().catch(Site.handleAxiosError);//.then(() => Site.unblockUI());
};

export const ActionCreators = {
    setSoccerMatches,
    setLiveSoccerMatches,
    getSoccerMatches,
    getLiveSoccerMatches
};