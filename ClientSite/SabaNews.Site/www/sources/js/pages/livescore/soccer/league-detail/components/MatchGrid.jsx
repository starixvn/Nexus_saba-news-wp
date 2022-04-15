import React, { PureComponent, Fragment } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { Form } from 'react-bootstrap';
import { ActionCreators } from '../actions';
import Match from '../../index/components/match-grid/Match';
import DateTimeService from '@jsroot/common/dateTimeService';
import CommonFunctions from '@jsroot/common/common-functions';

export class MatchGrid extends PureComponent {
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
            const key = DateTimeService.formatDateTime(currentItem.startTime, 'YYYY-MM-DD');
            if (!helper[key]) {
                helper[key] = {
                    key: key,
                    text: key,
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

    groupMatches = matches => {
        let stageIds = matches.map(item => item.stage ? item.stage.stageId : 0);
        stageIds = stageIds.filter((value, index, self) => {
            return self.indexOf(value) === index;
        });

        if (stageIds.length > 1) {
            return this.groupMatchesByStage(matches);
        } else {
            return this.groupMatchesByDate(matches);
        }
    };

    render() {
        let matches = this.props.matches;
        if (matches.length === 0) {
            return <div className="none-livescore">Không có trận đấu nào.</div>;
        }

        const searchKey = this.state.searchKey;
        if (searchKey.length > 0) {
            matches = matches.filter(match =>
                CommonFunctions.getTeamName(match.homeTeam).toLowerCase().indexOf(searchKey.toLowerCase()) !== -1
                || CommonFunctions.getTeamName(match.awayTeam).toLowerCase().indexOf(searchKey.toLowerCase()) !== -1);
        }

        if (matches.length > 0) {
            matches = this.groupMatches(matches);
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

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { searchKey } = state;
    return { searchKey };
};

export default connect(mapStateToProps, mapDispatchToProps)(MatchGrid);