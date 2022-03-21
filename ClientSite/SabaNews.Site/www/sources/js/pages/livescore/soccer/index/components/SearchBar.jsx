import React, { Component } from 'react';
import { Form } from 'react-bootstrap';

export default class SearchBar extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <div className="livescore-soccer-search-bar">
                <Form.Control type="text" placeholder="Tìm kiếm giải đấu, đội bóng..."
                    id="livescore-soccer-searchbox" className="livescore-soccer-searchbox" />
                <span className="btn-search-match"><i className="fa fa-search" /></span>
            </div>
        );
    }
}