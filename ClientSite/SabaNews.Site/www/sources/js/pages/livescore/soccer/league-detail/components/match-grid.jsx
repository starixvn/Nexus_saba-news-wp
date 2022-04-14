import React, { PureComponent } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import Match from '../../index/components/match-grid/Match';
import DateTimeService from '@jsroot/common/dateTimeService';

export class MatchGrid extends PureComponent {
    render() {
        let matches = this.props.matches;
        if (matches.length === 0) {
            return <div className="none-livescore">Không có trận đấu nào.</div>;
        }

        const helper = {};
        const { searchKey } = this.props;
        if (searchKey.length > 0) {
            matches = matches.filter(match => match.league.leagueFullNameEN.toLowerCase().indexOf(searchKey.toLowerCase()) !== -1
                || match.homeTeam.teamFullNameEN.toLowerCase().indexOf(searchKey.toLowerCase()) !== -1
                || match.awayTeam.teamFullNameEN.toLowerCase().indexOf(searchKey.toLowerCase()) !== -1);
        }

        if (matches.length === 0 && searchKey.length > 0) {
            return <div className="none-livescore">Không tìm thấy trận đấu nào phù hợp.</div>;
        }

        matches = matches.reduce((previousItem, currentItem) => {
            const key = DateTimeService.formatDateTime(currentItem.startTime, 'YYYY-MM-DD');
            if (!helper[key]) {
                helper[key] = {
                    date: key,
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
                    matches.map((item, index) => {
                        return (
                            <div key={index}>
                                <div>{item.date}</div>
                                <div>
                                    {
                                        item.map(match => <Match key={match.matchId} searchKey={searchKey} match={match} />)
                                    }
                                </div>
                            </div>
                        );
                    })
                }
            </div>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { searchKey } = state;
    return { searchKey };
};

export default connect(mapStateToProps, mapDispatchToProps)(MatchGrid);