import Site from '@jsroot/common/site';
import DataService from '@jsroot/common/dataService';
import {
    setMajorLeagues,
    setLeague,
    setMatches,
    setLiveMatches,
    setSeasonStandings
} from './leagueDetailSlice';

const getMajorLeagues = () => async dispatch => {
    const url = Site.resolveClientUrl('soccer/getMajorLeagues');
    await DataService.postDataAsync(url).then(response => {
        dispatch(setMajorLeagues(response.data));
    }).catch(Site.handleAxiosError);
};

const getMatchesBySeason = params => async dispatch => {
    const url = Site.resolveClientUrl('soccer/getMatchesBySeason');
    await DataService.postDataAsync(url, params).then(response => {
        const { league, matches } = response.data;
        dispatch(setLeague(league));
        dispatch(setMatches(matches));
    }).catch(Site.handleAxiosError);
};

const getSeasonStandings = params => async dispatch => {
    const url = Site.resolveClientUrl('soccer/getSeasonStandings');
    await DataService.postDataAsync(url, params).then(response => {
        dispatch(setSeasonStandings(response.data));
    }).catch(Site.handleAxiosError);
};

export const ActionCreators = {
    setMajorLeagues,
    setLeague,
    setMatches,
    setLiveMatches,
    setSeasonStandings,
    getMajorLeagues,
    getMatchesBySeason,
    getSeasonStandings
};