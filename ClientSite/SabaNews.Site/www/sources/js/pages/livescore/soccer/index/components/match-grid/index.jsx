import React, { PureComponent } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import moment from 'moment';
import SearchBar from '../SearchBar';
import Grid from './Grid';
import { ActionCreators } from '../../actions';
import CommonFunctions from '@jsroot/common/common-functions';
import DateTimeService from '@jsroot/common/dateTimeService';
import { LeagueType, MatchStatus } from '@jsroot/common/constants';

export class MatchGrid extends PureComponent {
    filterMatches = (matches, searchKey) => {
        return matches.filter(match => CommonFunctions.getLeagueName(match.league).toLowerCase().indexOf(searchKey.toLowerCase()) !== -1
            || CommonFunctions.getTeamName(match.homeTeam).toLowerCase().indexOf(searchKey.toLowerCase()) !== -1
            || CommonFunctions.getTeamName(match.awayTeam).toLowerCase().indexOf(searchKey.toLowerCase()) !== -1);
    };

    groupMatchesByStage = matches => {
        const totalItems = matches.length;
        const tempMatches = [];
        for (let i = 0; i < totalItems; i++) {
            const match = matches[i];
            const league = match.league;
            const key = `${league.leagueId}-${league.leagueType !== LeagueType.League && league.season ? league.season.seasonId : 0}`
                + `-${league.leagueType !== LeagueType.League && league.stage ? league.stage.stageId : 0}`;
            const item = {
                key,
                ...league,
                matches: [match]
            };
            if (tempMatches.length === 0) {
                tempMatches.push(item);
            } else {
                const index = tempMatches.findIndex(x => x.key === key);
                if (index === - 1) {
                    tempMatches.push(item);
                } else {
                    tempMatches[index].matches.push(match);
                }
            }
        }

        return tempMatches;
    };

    render() {
        let { matches, selectedDate, today, searchKey } = this.props;
        if (moment(selectedDate).isBefore(today) && !window.isLivePage) {
            matches = matches.filter(match => {
                const endTime = moment(DateTimeService.convertFromUTC(match.endTime)).format('YYYY-MM-DD');
                return match.status !== MatchStatus.NotStarted
                    && moment(endTime).isBefore(today);
            });
        }

        const totalMatches = matches.length;
        if (totalMatches === 0) {
            return <div className="none-livescore">
                { !window.isLivePage ? 'Không có trận đấu nào.' : 'Hiện tại không có trận đấu trực tiếp nào.' }
            </div>;
        }

        if (searchKey.length > 0) {
            matches = this.filterMatches(matches, searchKey);
        }

        const leagues = this.groupMatchesByStage(matches);

        return (
            <>
                <SearchBar />
                {
                    totalMatches === 0 && searchKey.length > 0 &&
                    <div className="none-livescore">Không tìm thấy giải đấu hoặc trận đấu nào phù hợp.</div>
                }
                {
                    totalMatches > 0 &&
                    <Grid leagues={leagues} selectedDate={selectedDate} searchKey={searchKey} />
                }
            </>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { selectedDate, today, searchKey } = state;
    return { selectedDate, today, searchKey };
};

export default connect(mapStateToProps, mapDispatchToProps)(MatchGrid);