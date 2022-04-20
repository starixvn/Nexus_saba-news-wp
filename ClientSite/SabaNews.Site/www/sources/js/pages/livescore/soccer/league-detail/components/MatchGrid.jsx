import React, { PureComponent } from 'react';
import { Form } from 'react-bootstrap';
import moment from 'moment';
import Match from '../../index/components/match-grid/Match';
import DateTimeService from '@jsroot/common/dateTimeService';
import CommonFunctions from '@jsroot/common/common-functions';
import LangHelper from '@jsroot/common/langHelper';

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

    getRoundName = round => `${LangHelper.getResource('Round')} ${round}`;

    getStageName = stage => stage ? stage.stageNameEN : '';

    groupMatchesByDate = matches => {
        const helper = {};
        return matches.reduce((previousItem, currentItem) => {
            const startTime = DateTimeService.convertFromUTC(currentItem.startTime);
            const key = DateTimeService.formatDateTime(startTime, 'YYYY-MM-DD');
            if (!helper[key]) {
                helper[key] = {
                    key: key,
                    text: DateTimeService.formatDateTime(key, 'dddd, DD/MM/YYYY'),
                    childs: [{ ...currentItem }]
                };
                previousItem.push(helper[key]);
            } else {
                helper[key].childs.push(currentItem);
            }

            return previousItem;
        }, []);
    };

    groupMatches = (stages, matches) => {
        if (stages.length <= 1 && matches.filter(item => item.round > 0).length === 0) {
            return this.groupMatchesByDate(matches);
        }

        let totalItems = matches.length;
        const tempMatches = [];
        for (let i = 0; i < totalItems; i++) {
            const match = matches[i];
            const stage = match.stage;
            const stageId = stage ? stage.stageId : 0;
            const round = match.round ? match.round : 0;
            const item = {
                stageId, stage, round,
                matches: [match]
            };

            if (tempMatches.length === 0) {
                tempMatches.push(item);
            } else {
                const index = tempMatches.findIndex(x => stageId === x.stageId && round === x.round);
                if (index === - 1) {
                    tempMatches.push(item);
                } else {
                    tempMatches[index].matches.push(match);
                }
            }
        }

        totalItems = tempMatches.length;
        const result = [];
        for (let i = 0; i < totalItems; i++) {
            const item = tempMatches[i];
            result.push({
                key: i,
                text: item.round > 0 ? this.getRoundName(item.round) : this.getStageName(item.stage),
                childs: this.groupMatchesByDate(item.matches)
            });
        }

        return result;
    };

    sortMatches = (matches, sortAsc) => {
        if (sortAsc) {
            return matches.sort((item1, item2) => moment(item1.startTime).diff(moment(item2.startTime)));
        } else {
            return matches.sort((item1, item2) => moment(item2.startTime).diff(moment(item1.startTime)));
        }
    };

    filterMatches = (matches, searchKey) => {
        return matches.filter(match =>
            CommonFunctions.getTeamName(match.homeTeam).toLowerCase().indexOf(searchKey.toLowerCase()) !== -1
            || CommonFunctions.getTeamName(match.awayTeam).toLowerCase().indexOf(searchKey.toLowerCase()) !== -1);
    };

    render() {
        let { stages, matches, sortAsc } = this.props;
        if (matches.length === 0) {
            return <div className="none-livescore">Không có trận đấu nào.</div>;
        }

        matches = this.sortMatches([...matches], sortAsc);
        const searchKey = this.state.searchKey;
        if (searchKey.length > 0) {
            matches = this.filterMatches(matches, searchKey);
        }

        if (matches.length > 0) {
            matches = this.groupMatches(stages, matches);
        }

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
                                                    if (child.key) {
                                                        return <div className="group-match-item" key={child.key}>
                                                            <div className="group-match-item-slogan">{child.text}</div>
                                                            {
                                                                child.childs.map(match => <Match key={match.matchId} searchKey={searchKey} match={match} />)
                                                            }
                                                        </div>;
                                                    } else {
                                                        return <Match key={child.matchId} searchKey={searchKey} match={child} />;
                                                    }
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