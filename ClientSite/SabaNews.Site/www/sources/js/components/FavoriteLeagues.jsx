import React, { PureComponent } from 'react';
import TeamIcon from '@jsroot/components/Icon/TeamIcon';
import LangHelper from '@jsroot/common/langHelper';
import Site from '@jsroot/common/site';
import DateTimeService from '@jsroot/common/dateTimeService';
import CommonFunctions from '@jsroot/common/common-functions';
import FavouriteLeagueIcon from '@jsroot/components/Icon/FavouriteLeagueIcon';
import { Modal } from 'react-bootstrap';
import { Button } from 'react-bootstrap';

export default class FavoriteLeagues extends  PureComponent  {
    constructor(props) {
        super(props);
        this.state = {
            leagues: props.leagues,
            showPopupSearch: false,
        };
    }

    componentDidUpdate(prevProps) {
        if (this.props.leagues !== prevProps.leagues) {
            this.setState({
                leagues: this.props.leagues
            });
        }
    }

    handleClick = (event, league) => {
        const className = event.target.className;
        if(className.includes('icon-livescore')) {
            return;
        }
        const leagueName = Site.toSlug(CommonFunctions.getLeagueName(league));
        const today = DateTimeService.formatDateTime(DateTimeService.getCurrentClientDateTime(), 'YYYY-MM-DD');
        const seasonId = league.currentSeasonId ? league.currentSeasonId : 0;
        const url = Site.resolveClientUrl(`lich-thi-dau/bong-da/${leagueName}/${seasonId}/${today}/${league.leagueId}`);
        if (event.ctrlKey) {
            window.open(url, '_blank');
        } else {
            window.location = url;
        }
    };

    sortLeagues = leagues => {
        const items = [...leagues];
        return items.sort((item1, item2) => (item2.order - item1.order));
    };

    onUpdateFavLeague = () => {
        const favLeagues = CommonFunctions.getFavLeagues();
        this.setState({leagues: favLeagues});
        if(typeof(this.props.onToggleIcon) === 'function') {
            this.props.onToggleIcon();
        }
    };

    handleOpenSearchPopup = () => {
        this.setState({showPopupSearch: true});
    }
    handleClose = () => {
        this.setState({showPopupSearch: false});
    }
    render()  {
        const leagues = this.sortLeagues(this.state.leagues);
        const { showPopupSearch } = this.state
        return (
            <div className="leagues">
                <div className="leagues-title">
                    <div className="leagues-group">
                        <i className="icon-livescore icon-live-start" />
                        <span>{LangHelper.getResource('HotLeague')}</span>
                    </div>
                    <i onClick={this.handleOpenSearchPopup} className="icon-livescore icon-close rotare-plus" />
                </div>
                <div className="leagues-main">
                    {
                        leagues.map(league => {
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
                <Modal className="modal-livescore" size="sm" show={showPopupSearch} onHide={this.handleClose}>
                    <Modal.Header>
                        <Modal.Title>Thêm giải đấu</Modal.Title>
                        <i className="icon-livescore icon-close" onClick={this.handleClose} />
                    </Modal.Header>
                    <Modal.Body>
                        <div class="livescore-soccer-search-bar">
                            <input placeholder="tìm kiếm giải đấu..." type="text" class="livescore-soccer-searchbox form-control" value="" />
                            <span class="btn-search-match"><i class="icon-livescore icon-search"></i></span>
                        </div>
                    </Modal.Body>
                </Modal>
            </div>
        );
    }
}