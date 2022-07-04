import React, { PureComponent } from 'react';
import TeamIcon from '@jsroot/components/Icon/TeamIcon';
import { Form } from 'react-bootstrap';
import Match from '../../index/components/match-grid/Match';
import DateTimeService from '@jsroot/common/dateTimeService';
import CommonFunctions from '@jsroot/common/common-functions';
import moment from 'moment';
import FavouriteLeagueIcon from '@jsroot/components/Icon/FavouriteLeagueIcon';


export default class MatchGrid extends PureComponent {
    constructor(props) {
        super(props);

        this.state = {
            searchKey: ''
        };
    }

    handleChangeSearchKey = event => {
        this.setState({ searchKey: event.target.value });
    };

    filterMatches = (matches, searchKey) => {
        return matches.filter(match =>
            CommonFunctions.getTeamName(match.homeTeam).toLowerCase().indexOf(searchKey.toLowerCase()) !== -1
            || CommonFunctions.getTeamName(match.awayTeam).toLowerCase().indexOf(searchKey.toLowerCase()) !== -1);
    };

    groupMatchesByLeague = matches => {
        const totalItems = matches.length;
        const tempMatches = [];
        for (let i = 0; i < totalItems; i++) {
            const match = matches[i];
            const key = match.league.leagueId;
            const item = {
                key,
                league: match.league,
                childs: [match]
            };
            if (tempMatches.length === 0) {
                tempMatches.push(item);
            } else {
                const index = tempMatches.findIndex(x => x.key === key);
                if (index === - 1) {
                    tempMatches.push(item);
                } else {
                    tempMatches[index].childs.push(match);
                }
            }
        }
        return tempMatches;
    };

    groupMatchesByDate = matches => {
        let totalItems = matches.length;
        const tmpMatches = [];
        for (let i = 0; i < totalItems; i++) {
            const match = matches[i];
            const startTime = DateTimeService.convertFromUTC(match.startTime);
            const key = DateTimeService.formatDateTime(startTime, 'YYYY-MM-DD');
            const item = {
                key,
                text: DateTimeService.formatDateTime(key, 'dddd - DD/MM/YYYY'),
                childs: [match]
            };
            if (tmpMatches.length === 0) {
                tmpMatches.push(item);
            } else {
                const index = tmpMatches.findIndex(x => x.key === key);
                if (index === - 1) {
                    tmpMatches.push(item);
                } else {
                    tmpMatches[index].childs.push(match);
                }
            }
        }

        totalItems = tmpMatches.length;
        const result = [];
        for (let i = 0; i < totalItems; i++) {
            const item = tmpMatches[i];
            result.push({
                key: item.key,
                text: item.text,
                childs: this.groupMatchesByLeague(item.childs)
            });
        }

        return result;
    };

    sortMatches = (matches, sortAsc) => {
        if (sortAsc) {
            return matches.sort((item1, item2) => moment(item1.key).diff(moment(item2.key)));
        } else {
            return matches.sort((item1, item2) => moment(item2.key).diff(moment(item1.key)));
        }
    };

    onUpdateFavouriteMatch = matchId => {
        this.props.onUpdateFavouriteMatch(matchId);
    };

    handleClickLeague = (event, league) => {
        const className = event.target.className;
        if (className.includes('icon-livescore')) {
            return;
        }
        const url = CommonFunctions.getLeaguePageUrl(league);
        if (event.ctrlKey) {
            window.open(url, '_blank');
        } else {
            window.location = url;
        }
    };

    render() {
        let { matches, sortAsc, isAllMatches } = this.props;
        if (matches.length === 0) {
            if (isAllMatches) {
                return (<div className="none-livescore">
                            Không có trận đấu nào. Nhấn vào biểu tượng <i className="icon-livescore icon-favourite-outline"></i> để thêm trận đấu quan tâm
                        </div>);
            }
            return <div className="none-livescore">Không có trận đấu nào.</div>;
        }
        const searchKey = this.state.searchKey;
        if (searchKey.length > 0) {
            matches = this.filterMatches(matches, searchKey);
        }
        if (matches.length > 0) {
            matches = this.groupMatchesByDate(matches);
        }
        matches = this.sortMatches(matches, sortAsc);

        return (
            <>
                <div className="group-searchbar">
                    <div className="livescore-soccer-search-bar">
                        <Form.Control type="text" placeholder="Tìm kiếm đội bóng..."
                            id="livescore-soccer-searchbox" className="livescore-soccer-searchbox"
                            value={searchKey}
                            onChange={this.handleChangeSearchKey}
                        />
                        <span className="btn-search-match"><i className="icon-livescore icon-search" /></span>
                    </div>
                </div>
                {
                    matches.length === 0 && searchKey.length > 0
                    && <div className="none-livescore">Không tìm thấy trận đấu nào phù hợp.</div>
                }
                <div className="soccer-match-grid">
                    {
                        matches.map(item => {
                            return (
                                <div className="soccer-match-grid" key={item.key}>
                                    <div className="soccer-match-grid-item">
                                        <div className="soccer-match-grid-item-header">{item.text}</div>
                                        <div className="soccer-match-grid-item-body">
                                            {
                                                item.childs.map(child => {
                                                    return <div className="group-match-item" key={child.key}>
                                                        <div className="group-match-item-slogan"
                                                            onClick={event => this.handleClickLeague(event, child.league)}>
                                                            <FavouriteLeagueIcon league={child.league} />
                                                            <TeamIcon src={child.league.icon} />  {CommonFunctions.getLeagueName(child.league)}
                                                        </div>
                                                        {
                                                            child.childs.map(match =>
                                                                <Match key={match.matchId}
                                                                    searchKey={searchKey} match={match}
                                                                    onUpdateFavouriteMatch={this.onUpdateFavouriteMatch}
                                                                />
                                                            )
                                                        }
                                                    </div>;
                                                })
                                            }
                                        </div>
                                    </div>
                                </div>
                            );
                        })
                    }
                </div>
            </>
        );
    }
}