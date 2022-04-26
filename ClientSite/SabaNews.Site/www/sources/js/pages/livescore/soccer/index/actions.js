import Site from '@jsroot/common/site';
import DataService from '@jsroot/common/dataService';
import {
    setIsMobile,
    setSearchKey,
    setSelectedDate,
    setDateRanges,
    setMajorLeagues,
    setMatches,
    setLiveMatches,
    setFavouriteMatches
} from './soccerMatchSlice';

const getMajorLeagues = () => async dispatch => {
    const url = Site.resolveClientUrl('soccer/getMajorLeagues');
    await DataService.postDataAsync(url).then(response => {
        dispatch(setMajorLeagues(response.data));
    }).catch(Site.handleAxiosError);
};

const getMatchesByDate = params => async dispatch => {
    const url = Site.resolveClientUrl('soccer/getMatches');
    await DataService.postDataAsync(url, params).then(response => {
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
    let matchIds = localStorage.getItem("favourite-matches");
    if(!matchIds) {
        matchIds = [];
    } else {
        matchIds = JSON.parse(matchIds);
    }
    dispatch(setFavouriteMatches(matchIds))
}

export const ActionCreators = {
    setIsMobile,
    setSearchKey,
    setSelectedDate,
    setDateRanges,
    setMajorLeagues,
    setMatches,
    setFavouriteMatches,
    getMajorLeagues,
    getMatchesByDate,
    getLiveMatches,
    getFavouriteMatches,
    setLiveMatches
};