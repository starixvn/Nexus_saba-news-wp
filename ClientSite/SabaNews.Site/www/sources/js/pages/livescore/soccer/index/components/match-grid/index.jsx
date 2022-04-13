import React, { PureComponent } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import League from './League';
import { ActionCreators } from '../../actions';

export class MatchGrid extends PureComponent {
    render() {
        if (this.props.matches.length === 0) {
            return <div className="none-livescore">
                { !window.isLivePage ? 'Không có trận đấu nào.' : 'Hiện tại không có trận đấu trực tiếp nào.' }
            </div>;
        }

        const helper = {};
        const { selectedDate, searchKey } = this.props;
        let matches = this.props.matches;
        if (searchKey.length > 0) {
            matches = matches.filter(match => match.league.leagueFullNameEN.toLowerCase().indexOf(searchKey.toLowerCase()) !== -1
                || match.homeTeam.teamFullNameEN.toLowerCase().indexOf(searchKey.toLowerCase()) !== -1
                || match.awayTeam.teamFullNameEN.toLowerCase().indexOf(searchKey.toLowerCase()) !== -1);
        }

        if (matches.length === 0 && searchKey.length > 0) {
            return <div className="none-livescore">Không tìm thấy trận đấu nào phù hợp.</div>;
        }

        matches = matches.reduce((previousItem, currentItem) => {
            const key = currentItem.league.leagueId
                + `-${currentItem.league.season ? currentItem.league.season.seasonId : 0}`
                + `-${currentItem.league.stage ? currentItem.league.stage.stageId : 0}`;
            if (!helper[key]) {
                helper[key] = {
                    leagueId: currentItem.league.leagueId,
                    leagueNameEN: currentItem.league.leagueNameEN,
                    leagueFullNameEN: currentItem.league.leagueFullNameEN,
                    icon: currentItem.league.icon,
                    leagueType: currentItem.league.leagueType,
                    season: currentItem.league.season,
                    stage: currentItem.league.stage,
                    matches: [{ ...currentItem }]
                };
                previousItem.push(helper[key]);
            } else {
                helper[key].matches.push(currentItem);
            }

            return previousItem;
        }, []);

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