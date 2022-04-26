import React from 'react';
import { ThemeProvider } from 'react-bootstrap';
import Site from '@jsroot/common/site';
import CommonFunctions from '@jsroot/common/common-functions';

const FavoriteMatches = () => {

    const handleClick = event => {
        const url = Site.resolveClientUrl(`lich-thi-dau/bong-da/tran-dau-quan-tam`);
        if (event.ctrlKey) {
            window.open(url, '_blank');
        } else {
            window.location = url;
        }
    }
    const matches = CommonFunctions.getFavouriteMatches();
    return (
        <div className="livescore-like" onClick={handleClick}>
            <i className="icon-livescore icon-live-start" /> <span className="title">Trận đấu quan tâm</span>
            <span className="number-like">{ matches.length }</span>
        </div>
    );
};

export default FavoriteMatches;