import React, { PureComponent } from 'react';
import { Form } from 'react-bootstrap';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import FavoriteMatches from '@jsroot/components/FavoriteMatches';

export class SearchBar extends PureComponent {
    onChange = event => {
        this.props.setSearchKey(event.target.value);
    };

    render() {
        return (
            <div className="group-searchbar">
                <div className="livescore-soccer-search-bar">
                    <Form.Control type="text" placeholder="Tìm kiếm giải đấu, đội bóng..."
                        id="livescore-soccer-searchbox" className="livescore-soccer-searchbox"
                        value={this.props.searchKey}
                        onChange={this.onChange}
                    />
                    <span className="btn-search-match"><i className="icon-livescore icon-search" /></span>
                </div>
                <FavoriteMatches isMobile />
            </div>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { searchKey } = state;
    return { searchKey };
};

export default connect(mapStateToProps, mapDispatchToProps)(SearchBar);