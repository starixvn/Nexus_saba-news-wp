import Site from '@jsroot/common/site';
import DataService from '@jsroot/common/dataService';
import {
    setMajorLeagues,
    setMatches,
    setLiveMatches,
    setFavouriteMatches
} from './favouriteMatchSlice';

const getMajorLeagues = () => async dispatch => {
    const url = Site.resolveClientUrl('soccer/getMajorLeagues');
    await DataService.postDataAsync(url).then(response => {
        dispatch(setMajorLeagues(response.data));
    }).catch(Site.handleAxiosError);
};

const getMatches = () => async dispatch => {
    const matchIds = localStorage.getItem("favourite-matches");
    const param = {
        matchIds: matchIds
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

const getFavouriteMatches = () => async dispatch => {
    const matchIds = localStorage.getItem("favourite-matches");
    dispatch(setFavouriteMatches(JSON.parse(matchIds)))
}

export const ActionCreators = {
    setMajorLeagues,
    setMatches,
    setLiveMatches,
    setFavouriteMatches,
    getMajorLeagues,
    getMatches,
    getLiveMatches,
    getFavouriteMatches
};