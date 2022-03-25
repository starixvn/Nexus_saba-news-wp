import React, { PureComponent } from 'react';
import { Form } from 'react-bootstrap';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';

export class SearchBar extends PureComponent {
    render() {
        return (
            <div className="livescore-soccer-search-bar">
                <Form.Control type="text" placeholder="Tìm kiếm giải đấu, đội bóng..."
                    id="livescore-soccer-searchbox" className="livescore-soccer-searchbox"
                    value={this.props.searchKey}
                    onChange={event => this.props.setSearchKey(event.target.value)}
                />
                <span className="btn-search-match"><i className="fa fa-search" /></span>
            </div>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { searchKey } = state;
    return {
        searchKey
    };
};

export default connect(mapStateToProps, mapDispatchToProps)(SearchBar);