import React, { PureComponent } from 'react';
import { Modal, Form } from 'react-bootstrap';
import CommonFunctions from '@jsroot/common/common-functions';
import TeamIcon from '@jsroot/components/Icon/TeamIcon';
import FavouriteLeagueIcon from '@jsroot/components/Icon/FavouriteLeagueIcon';
import PropTypes from 'prop-types';

export default class SearchLeague extends  PureComponent  {
    static propTypes = {
        searchKey: PropTypes.string,
        isShowResult: PropTypes.bool,
        leagues: PropTypes.oneOfType([PropTypes.arrayOf(PropTypes.string), PropTypes.arrayOf(PropTypes.object)]),
        onClose: PropTypes.func
    };

    static defaultProps = {
        searchKey: '',
        isShowResult: false,
        leagues: []
    };

    constructor(props) {
        super(props);
        this.state = {
            searchKey: '',
            isShowResult: false,
            leagues: []
        };
    }

    componentDidUpdate(prevProps) {
        if (this.props.leagues !== prevProps.leagues || !this.state.isShowResult) {
            this.setState({
                leagues: this.props.leagues,
                isShowResult: true
            });
        }
    }

    handleChange = event => {
        const searchKey = event.target.value;
        this.setState({searchKey: searchKey});
        const leagues = this.props.leagues;
        let result = null;
        if (searchKey.length > 2) {
            result = leagues.filter(item =>
                CommonFunctions.getLeagueName(item).toLowerCase().indexOf(searchKey.toLowerCase()) !== -1);
            this.setState({leagues: result, isShowResult: true});
        }
    };

    handleClose = () => {
        this.props.onClose();
    };

    render()  {
        const { isShowSearchPopup } = this.props;
        const { isShowResult, leagues, searchValue } = this.state;
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
                            <div className={`modal-data ${isShowResult && leagues.length ? 'spacing-top' : ''}`}>
                            {
                                isShowResult && leagues.length > 0 &&
                                <div className="livescore-soccer-extension modal-data">
                                    {
                                        leagues.map((league, index) => {
                                            const reg = new RegExp(`(${searchValue})`, 'gi');
                                            const displayValue = CommonFunctions.getLeagueName(league)
                                                .replace(reg, "<span class='text-matched'>$1</span>");

                                            return (
                                                <div key={index} className="leagues-item" onClick={event => this.handleClick(event, league)}>
                                                    <div className="leagues-name">
                                                        <TeamIcon className="logo" src={league.icon} />
                                                        <span className="text">{displayValue}</span>
                                                    </div>
                                                    <div className="toggle-league">
                                                        <FavouriteLeagueIcon league={league} onUpdateFavLeague={this.onUpdateFavLeague}/>
                                                    </div>
                                                </div>
                                            );
                                        })
                                    }
                                </div>
                            }
                            {
                                isShowResult && leagues.length === 0 &&
                                <p className="not-found">Không tìm thấy giải đấu phù hợp</p>
                            }
                            </div>
                        </div>
                    </Modal.Body>
                </Modal>
            </>
        );
    }
}