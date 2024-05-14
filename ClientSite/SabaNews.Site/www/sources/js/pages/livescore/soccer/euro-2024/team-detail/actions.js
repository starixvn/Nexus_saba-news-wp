import Site from '@jsroot/common/site';
import DataService from '@jsroot/common/dataService';

import {
    setMatches,
    setPlayers
} from './teamDetailSlice';

const getTeamData = teamId => async dispatch => {
    var param = {teamId: teamId};
    const url = Site.resolveClientUrl('euro2024/GetTeamDetailData');
    await DataService.postDataAsync(url, param).then(response => {
        const { matches, players, team } = response.data;
        dispatch(setMatches(matches));
        dispatch(setPlayers(players));
    }).catch(Site.handleAxiosError);
};

export const ActionCreators = {
    setMatches,
    setPlayers,
    getTeamData
};