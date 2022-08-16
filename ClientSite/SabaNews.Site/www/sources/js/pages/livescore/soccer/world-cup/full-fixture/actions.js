import Site from '@jsroot/common/site';
import DataService from '@jsroot/common/dataService';
import { WorldCup } from '@jsroot/common/constants';

import {
    setMatches,
    setLeague,
    setSelectedStageId
} from './fullFixtureSlice';

const getMatches = () => async dispatch => {
    const url = Site.resolveClientUrl('soccer/GetMatchesBySeason');
    await DataService.postDataAsync(url, WorldCup).then(response => {
        const { league, matches } = response.data;
        dispatch(setLeague(league));
        dispatch(setMatches(matches));
    }).catch(Site.handleAxiosError);
};

export const ActionCreators = {
    setMatches,
    setLeague,
    setSelectedStageId,
    getMatches
};