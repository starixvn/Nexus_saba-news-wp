import Site from '@jsroot/common/site';
import DataService from '@jsroot/common/dataService';
import { Euro } from '../euro-constants';
import { WorldCup } from '@jsroot/common/constants';

import {
    setMatches,
    setLeague,
} from './standingSlice';

const getMatches = () => async dispatch => {
    const url = Site.resolveClientUrl('soccer/getMatchesBySeason');
    var param = {
        leagueId: WorldCup.leagueId,
        seasonId: WorldCup.seasonId
    }
    await DataService.postDataAsync(url, param).then(response => {
        const { league, matches } = response.data;
        dispatch(setLeague(league));
        dispatch(setMatches(matches));
    }).catch(Site.handleAxiosError);
};

export const ActionCreators = {
    setMatches,
    setLeague,
    getMatches
};