import Site from '@jsroot/common/site';
import DataService from '@jsroot/common/dataService';
import { WorldCup } from '@jsroot/common/constants';

import {
    setMatches,
    setPlayers,
} from './minigameSlice';

const getMatches = () => async dispatch => {
    const url = Site.resolveClientUrl('soccer/getMatchesBySeason');
    var param = {
        leagueId: WorldCup.leagueId,
        seasonId: WorldCup.seasonId
    }
    await DataService.postDataAsync(url, param).then(response => {
        const { matches } = response.data;
        dispatch(setMatches(matches));
    }).catch(Site.handleAxiosError);
};

export const ActionCreators = {
    setMatches,
    setPlayers,
    getMatches
};