import React, { PureComponent } from 'react';
import { Form } from 'react-bootstrap';
import moment from 'moment';
import Match from '../../index/components/match-grid/Match';
import DateTimeService from '@jsroot/common/dateTimeService';
import CommonFunctions from '@jsroot/common/common-functions';

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

    groupMatchesByStage = matches => {
        const helper = {};
        matches = matches.reduce((previousItem, currentItem) => {
            const key = currentItem.stage ? currentItem.stage.stageId : 0;
            if (!helper[key]) {
                helper[key] = {
                    key: key,
                    text: currentItem.stage ? currentItem.stage.stageNameEN : '',
                    orderIndex: currentItem.stage.orderIndex,
                    childs: [{ ...currentItem }]
                };
                previousItem.push(helper[key]);
            } else {
                helper[key].childs.push(currentItem);
            }

            return previousItem;
        }, []);

        matches.sort((item1, item2) => item2.orderIndex - item1.orderIndex);
        const totalItems = matches.length;
        for (let i = 0; i < totalItems; i++) {
            matches[i] = { ...matches[i], childs: this.groupMatchesByDate(matches[i].childs) }
        }

        return matches;
    };

    groupMatches = (stages, matches) => {
        if (stages.length > 1) {
            return this.groupMatchesByStage(matches);
        } else {
            return this.groupMatchesByDate(matches);
        }
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