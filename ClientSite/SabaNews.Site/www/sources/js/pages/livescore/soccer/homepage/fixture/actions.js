import Site from '@jsroot/common/site';
import DataService from '@jsroot/common/dataService';

import {
    setLeagues,
    setLeague,
    setLiveMatches
} from './fixtureHomepageSlice';

const getLeagues= () => async dispatch => {
    const url = Site.resolveClientUrl('api/GetHomeFixture');
    await DataService.postDataAsync(url).then(response => {
        dispatch(setLeagues(response.data));
    }).catch(Site.handleAxiosError);
};

export const ActionCreators = {
    setLeagues,
    setLeague,
    setLiveMatches,
    getLeagues
};