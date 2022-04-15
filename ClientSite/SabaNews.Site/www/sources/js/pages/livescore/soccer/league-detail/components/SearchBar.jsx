import React from 'react';
import { Form } from 'react-bootstrap';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';

const SearchBar = props => {
    return (
        <div className="livescore-soccer-search-bar">
            <Form.Control type="text" placeholder="Tìm kiếm đội bóng..."
                id="livescore-soccer-searchbox" className="livescore-soccer-searchbox"
                value={props.searchKey}
                onChange={event => props.setSearchKey(event.target.value)}
            />
            <span className="btn-search-match"><i className="icon-livescore icon-search" /></span>
        </div>
    );
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { searchKey } = state;
    return { searchKey };
};

export default connect(mapStateToProps, mapDispatchToProps)(SearchBar);