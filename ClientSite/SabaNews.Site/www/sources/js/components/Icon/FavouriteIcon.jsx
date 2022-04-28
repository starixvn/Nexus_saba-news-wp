import React, { PureComponent } from 'react';
import LangHelper from '@jsroot/common/langHelper';
import Tooltip from 'react-bootstrap/Tooltip';
import OverlayTrigger from 'react-bootstrap/OverlayTrigger';
import { toast } from 'react-toastify';
import Site from '@jsroot/common/site';
import CommonFunctions from '@jsroot/common/common-functions';
toast.configure();

export default class FavouriteIcon extends PureComponent {
    constructor(props) {
        super(props);

        const matchId = this.props.matchId;
        const favMatches = CommonFunctions.getFavouriteMatches();
        const matchIndex = favMatches.findIndex(item => item === matchId);
        this.state = {
            isFavourite: matchIndex >= 0,
            isShowTooltip: true
        };
    }

    handleUpdateFavourite = () => {
        const matchId = this.props.matchId;
        const isFavourite = CommonFunctions.updateFavouriteMatch(matchId);
        let message = "";
        if(isFavourite) {
            const url = Site.resolveClientUrl(`lich-thi-dau/bong-da/tran-dau-quan-tam/`);
            message = <div className="fav-match-add"><span>Thêm vào Trận đấu quan tâm</span> | <a href={url}>hiển thị</a></div>;
        } else {
            message = <div className="fav-match-remove"><span>Xóa khỏi Trận đấu quan tâm</span></div>;
        }
        const toastId = toast.dark(message, {
            toastId: 'fav',
            position: toast.POSITION.BOTTOM_CENTER,
            hideProgressBar: true,
            autoClose: 3000
        });
        toast.update(toastId, { render: () => message, autoClose: 3000 });
        this.setState({ isFavourite: isFavourite, isShowTooltip: false});
    }

    renderTooltip = () => {
        const { isFavourite } = this.state;
        return (
            <Tooltip className="tooltip-add-favourite-match" >
            { isFavourite ? LangHelper.getResource('RemoveFavouriteMatchTooltip') :  LangHelper.getResource('AddFavouriteMatchTooltip')}
            </Tooltip>
        )
    };

    onMouseOut = () => {
        const isShowTooltip = this.state.isShowTooltip;
        if(!isShowTooltip) {
            this.setState({isShowTooltip: true});
        }
    }


    render() {
        const { isFavourite, isShowTooltip } = this.state;
        return (
            <>
                {
                    isShowTooltip ?
                    <OverlayTrigger
                        placement="bottom"
                        trigger={['hover', 'focus']}
                        overlay={this.renderTooltip()}
                    >
                        <i onClick={this.handleUpdateFavourite} onMouseOut={this.onMouseOut} className={`icon-livescore ${isFavourite ? "icon-live-start start-active" : "icon-favourite-outline"}`} />
                    </OverlayTrigger> :
                    <i onClick={this.handleUpdateFavourite} onMouseOut={this.onMouseOut} className={`icon-livescore ${isFavourite ? "icon-live-start start-active" : "icon-favourite-outline"}`} />
                }
            </>
        );
    }
}