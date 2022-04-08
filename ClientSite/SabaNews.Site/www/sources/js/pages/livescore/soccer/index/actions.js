import Site from '@jsroot/common/site';
import DataService from '@jsroot/common/dataService';
import {
    setIsMobile,
    setSearchKey,
    setSelectedDate,
    setDateRanges,
    setMajorLeagues,
    setMatches,
    setLiveMatches
} from './soccerMatchSlice';

const getMajorLeagues = () => async dispatch => {
    const url = Site.resolveClientUrl('soccer/getMajorLeagues');
    await DataService.postDataAsync(url).then(response => {
        dispatch(setMajorLeagues(response.data));
    }).catch(Site.handleAxiosError);
};

const getMatches = params => async dispatch => {
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

export const ActionCreators = {
    setIsMobile,
    setSearchKey,
    setSelectedDate,
    setDateRanges,
    setMajorLeagues,
    setMatches,
    getMajorLeagues,
    getMatches,
    getLiveMatches,
    setLiveMatches
};