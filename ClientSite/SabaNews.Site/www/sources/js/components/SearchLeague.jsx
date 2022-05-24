import React, { PureComponent } from 'react';
import { Modal, Form } from 'react-bootstrap';
import CommonFunctions from '@jsroot/common/common-functions';
import TeamIcon from '@jsroot/components/Icon/TeamIcon';
import FavouriteLeagueIcon from '@jsroot/components/Icon/FavouriteLeagueIcon';
import PropTypes from 'prop-types';
import Site from '@jsroot/common/site';
import DateTimeService from '@jsroot/common/dateTimeService';

export default class SearchLeague extends  PureComponent  {
    static propTypes = {
        searchKey: PropTypes.string,
        leagues: PropTypes.oneOfType([PropTypes.arrayOf(PropTypes.string), PropTypes.arrayOf(PropTypes.object)]),
        favLeagues: PropTypes.oneOfType([PropTypes.arrayOf(PropTypes.string), PropTypes.arrayOf(PropTypes.object)]),
        toggleIcon: PropTypes.bool,
        onClose: PropTypes.func
    };

    static defaultProps = {
        searchKey: '',
        leagues: [],
        favLeagues: [],
        toggleIcon: false
    };

    constructor(props) {
        super(props);
        this.state = {
            searchKey: '',
            leagues: [],
            favLeagues: props.favLeagues
        };
    }

    componentDidUpdate(prevProps) {
        if (this.props.favLeagues !== prevProps.favLeagues) {
            this.setState({
                favLeagues: this.props.favLeagues
            });
        }
    }

    handleChange = event => {
        const searchKey = event.target.value;
        this.setState({searchKey: searchKey});
        const searchLeagues = this.props.searchLeagues;
        let result = null;
        if (searchKey.length > 2) {
            result = searchLeagues.filter(item =>
                CommonFunctions.getLeagueName(item).toLowerCase().indexOf(searchKey.toLowerCase()) !== -1);
            this.setState({leagues: result, toggleIcon: !this.state.toggleIcon });
        }
        if(searchKey === '') {
            this.setState({toggleIcon: !this.state.toggleIcon });
        }
    };

    handleClose = () => {
        this.props.onClose();
        this.setState({searchKey: ''});
    };

    handleClick = (event, league) => {
        const className = event.target.className;
        if(className.includes('icon-livescore')) {
            return;
        }
        const season = league.currentSeasonId ? league.currentSeasonId : 0;
        const today = DateTimeService.formatDateTime(DateTimeService.getCurrentClientDateTime(), 'YYYY-MM-DD');
        const leagueName = Site.toSlug(CommonFunctions.getLeagueName(league));
        const url = Site.resolveClientUrl(`lich-thi-dau/bong-da/${leagueName}/${season}/${today}/${league.leagueId}`);
        if (event.ctrlKey) {
            window.open(url, '_blank');
        } else {
            window.location = url;
        }
    };

    getLeagues = () => {
        if(this.state.searchKey === '') {
            return this.state.favLeagues;
        }
        return this.state.leagues;
    };

    onUpdateFavLeague = () => {
        this.props.onUpdateFavLeague();
        this.setState({toggleIcon: !this.state.toggleIcon });
    }

    render()  {
        const { isShowSearchPopup } = this.props;
        const { searchKey, toggleIcon } = this.state;
        const leagues = this.getLeagues();
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
                        </div>
                        <div className={`modal-data ${this.state.leagues.length ? 'spacing-top' : ''}`}>
                            {
                                leagues.length > 0 &&
                                <div className="livescore-soccer-extension">
                                    {
                                        leagues.map((league, index) => {
                                            const reg = new RegExp(`(${searchKey})`, 'gi');
                                            const leagueName = CommonFunctions.getLeagueName(league)
                                                .replace(reg, "<span class='text-matched'>$1</span>");

                                            return (
                                                <div key={index} className="leagues-item" onClick={event => this.handleClick(event, league)}>
                                                    <div className="leagues-name">
                                                        <TeamIcon className="logo" src={league.icon} />
                                                        <span className="text" dangerouslySetInnerHTML={{ __html: leagueName }}/>
                                                    </div>
                                                    <div className="toggle-league">
                                                        <FavouriteLeagueIcon league={league} onUpdateFavLeague={this.onUpdateFavLeague} flagToggle={toggleIcon} />
                                                    </div>
                                                </div>
                                            );
                                        })
                                    }
                                </div>
                            }
                            {
                                this.state.leagues.length === 0 && searchKey.length >= 3 &&
                                <p className="not-found">Không tìm thấy giải đấu phù hợp</p>
                            }
                        </div>
                    </Modal.Body>
                </Modal>
            </>
        );
    }
}