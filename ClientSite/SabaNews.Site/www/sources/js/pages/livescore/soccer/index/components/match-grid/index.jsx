import React, { PureComponent } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import League from './League';
import { ActionCreators } from '../../actions';
import CommonFunctions from '@jsroot/common/common-functions';
import { LeagueType } from '@jsroot/common/constants';

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
            const key = league.leagueId
                + `-${league.leagueType !== LeagueType.League && league.season ? league.season.seasonId : 0}`
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
        if (this.props.matches.length === 0) {
            return <div className="none-livescore">
                { !window.isLivePage ? 'Không có trận đấu nào.' : 'Hiện tại không có trận đấu trực tiếp nào.' }
            </div>;
        }

        const { selectedDate, searchKey } = this.props;
        let matches = this.props.matches;
        if (searchKey.length > 0) {
            matches = this.filterMatches(matches, searchKey);
        }

        if (matches.length === 0 && searchKey.length > 0) {
            return <div className="none-livescore">Không tìm thấy trận đấu nào phù hợp.</div>;
        }

        matches = this.groupMatchesByStage(matches);

        return (
            <div className="soccer-match-grid">
                {
                    matches.map((league, index) => <League key={index} selectedDate={selectedDate} searchKey={searchKey} league={league} />)
                }
            </div>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { selectedDate, searchKey } = state;
    return { selectedDate, searchKey };
};

export default connect(mapStateToProps, mapDispatchToProps)(MatchGrid);