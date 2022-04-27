import React from 'react';
import { Form } from 'react-bootstrap';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import CommonFunctions from '@jsroot/common/common-functions';

const SearchBar = props => {
    const favMatches = CommonFunctions.getFavouriteMatches();
    return (
        <div className="group-searchbar">
            <div className="livescore-soccer-search-bar">
                <Form.Control type="text" placeholder="Tìm kiếm giải đấu, đội bóng..."
                    id="livescore-soccer-searchbox" className="livescore-soccer-searchbox"
                    value={props.searchKey}
                    onChange={event => props.setSearchKey(event.target.value)}
                />
                <span className="btn-search-match"><i className="icon-livescore icon-search" /></span>
            </div>
            <div className="livescore-like">
                <span>Trận đấu quan tâm</span>
                <div className="count-start">
                    <i className="icon-livescore icon-live-start" data-badge={favMatches.length}></i>
                </div>
            </div>
        </div>
    );
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { searchKey } = state;
    return { searchKey };
};

export default connect(mapStateToProps, mapDispatchToProps)(SearchBar);