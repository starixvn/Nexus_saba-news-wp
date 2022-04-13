import Site from '@jsroot/common/site';
import DataService from '@jsroot/common/dataService';
import {
    setSearchKey,
    setMajorLeagues
} from './leagueDetailSlice';

const getMajorLeagues = () => async dispatch => {
    const url = Site.resolveClientUrl('soccer/getMajorLeagues');
    await DataService.postDataAsync(url).then(response => {
        dispatch(setMajorLeagues(response.data));
    }).catch(Site.handleAxiosError);
};

export const ActionCreators = {
    setSearchKey,
    setMajorLeagues,
    getMajorLeagues
};