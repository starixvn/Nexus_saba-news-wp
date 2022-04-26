import Site from '@jsroot/common/site';
import DataService from '@jsroot/common/dataService';
import CommonFunctions from '@jsroot/common/common-functions';

import {
    setMajorLeagues,
    setMatches,
    setLiveMatches
} from './favouriteMatchSlice';

const getMajorLeagues = () => async dispatch => {
    const url = Site.resolveClientUrl('soccer/getMajorLeagues');
    await DataService.postDataAsync(url).then(response => {
        dispatch(setMajorLeagues(response.data));
    }).catch(Site.handleAxiosError);
};

const getMatches = () => async dispatch => {
    const matchIds = CommonFunctions.getFavouriteMatches();
    const param = {
        matchIds: JSON.stringify(matchIds)
    };
    const url = Site.resolveClientUrl('soccer/GetMatchByIds');
    await DataService.postDataAsync(url, param).then(response => {
        dispatch(setMatches(response.data));
    }).catch(Site.handleAxiosError);
};

const getLiveMatches = () => async dispatch => {
    const url = Site.resolveClientUrl('soccer/getLiveMatches');
    await DataService.postDataAsync(url).then(response => {
        dispatch(setMatches(response.data));
    }).catch(Site.handleAxiosError);
};

export const ActionCreators = {
    setMajorLeagues,
    setMatches,
    setLiveMatches,
    getMajorLeagues,
    getMatches,
    getLiveMatches
};