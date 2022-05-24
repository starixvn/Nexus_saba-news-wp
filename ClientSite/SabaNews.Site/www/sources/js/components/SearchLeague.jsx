import React, { PureComponent } from 'react';
import { Modal } from 'react-bootstrap';
import { Form } from 'react-bootstrap';
import CommonFunctions from '@jsroot/common/common-functions';
import TeamIcon from '@jsroot/components/Icon/TeamIcon';
import FavouriteLeagueIcon from '@jsroot/components/Icon/FavouriteLeagueIcon';

export default class SearchLeague extends  PureComponent  {
    constructor(props) {
        super(props);
        this.state = {
            searchKey: '',
            isShowResult: false,
            result: []
        };
    }

    handleChange = event => {
        const searchKey = event.target.value;
        this.setState({searchKey: searchKey});
        const leagues = this.props.leagues;
        let result = null;
        if (searchKey.length > 2) {
            result = leagues.filter(item =>
                CommonFunctions.getLeagueName(item).toLowerCase().indexOf(searchKey.toLowerCase()) !== -1
                || CommonFunctions.getLeagueName(item).toLowerCase().indexOf(searchKey.toLowerCase()) !== -1);
            this.setState({result: result, isShowResult: true});
        }
    };

    handleClose = () => {
        this.props.onClose();
    };

    render()  {
        const { isShowSearchPopup } = this.props;
        const { isShowResult, result, searchValue } = this.state;
        return (
            <>
                <Modal className="modal-livescore" size="sm" show={isShowSearchPopup} onHide={this.handleClose}>
                    <Modal.Header>
                        <Modal.Title>Thêm giải đấu</Modal.Title>
                        <i className="icon-livescore icon-close" onClick={this.handleClose} />
                    </Modal.Header>
                    <Modal.Body>
                        <div className="livescore-soccer-search-bar">
                        <Form.Control type="text" placeholder="tìm kiếm giải đấu..."
                            className="livescore-soccer-searchbox form-control"
                            onChange={this.handleChange}
                        />
                            <span className="btn-search-match"><i className="icon-livescore icon-search"></i></span>
                            {
                                isShowResult &&
                                <div className="autocomplete-items-container">
                                    <div className="livescore-soccer-extension">
                                        {
                                            result.map((league, index) => {
                                                const reg = new RegExp(`(${searchValue})`, 'gi');
                                                const displayValue = CommonFunctions.getLeagueName(league)
                                                    .replace(reg, "<span class='text-matched'>$1</span>");

                                                return (
                                                    <div key={league.leagueId} className="leagues-item" onClick={event => this.handleClick(event, league)}>
                                                        <div className="leagues-name">
                                                            <TeamIcon className="logo" src={league.icon} />
                                                            <span className="text">{CommonFunctions.getLeagueName(league)}</span>
                                                        </div>
                                                        <div className="toggle-league">
                                                            <FavouriteLeagueIcon league={league} onUpdateFavLeague={this.onUpdateFavLeague}/>
                                                        </div>
                                                    </div>
                                                );
                                            })
                                        }
                                    </div>
                                </div>
                            }
                            <p className="not-found">Không tìm thấy giải đấu phù hợp</p>
                        </div>
                    </Modal.Body>
                </Modal>
            </>
        );
    }
}