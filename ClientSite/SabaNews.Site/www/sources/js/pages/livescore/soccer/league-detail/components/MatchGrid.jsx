import React, { PureComponent } from 'react';
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

    render() {
        let matches = this.props.matches;
        if (matches.length === 0) {
            return <div className="none-livescore">Không có trận đấu nào.</div>;
        }

        const helper = {};
        const searchKey = this.state.searchKey;
        if (searchKey.length > 0) {
            matches = matches.filter(match =>
                CommonFunctions.getTeamName(match.homeTeam).toLowerCase().indexOf(searchKey.toLowerCase()) !== -1
                || CommonFunctions.getTeamName(match.awayTeam).toLowerCase().indexOf(searchKey.toLowerCase()) !== -1);
        }

        if (matches.length > 0) {
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
                        matches.map((item, index) => {
                            return (
                                <div className="soccer-match-grid" key={index}>
                                    <div className="soccer-match-grid-item">
                                        <div className="soccer-match-grid-item-header">{item.date}</div>
                                        <div className="soccer-match-grid-item-body">
                                            <div className="group-match-item">
                                                <div className="group-match-item-slogan">Tứ kết</div>
                                                {
                                                    item.matches.map(match => <Match key={match.matchId} searchKey={searchKey} match={match} />)
                                                }
                                            </div>
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