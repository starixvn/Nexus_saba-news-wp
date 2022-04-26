import React from 'react';
import { ThemeProvider } from 'react-bootstrap';
import Site from '@jsroot/common/site';

const FavoriteMatches = ({ matches }) => {

    const handleClick = event => {
        const url = Site.resolveClientUrl(`lich-thi-dau/bong-da/tran-dau-quan-tam`);
        if (event.ctrlKey) {
            window.open(url, '_blank');
        } else {
            window.location = url;
        }
    }
    return (
        <div className="livescore-like" onClick={handleClick}>
            <i className="icon-livescore icon-live-start" /> <span className="title">Trận đấu quan tâm</span>
            <span className="number-like">{ matches ? matches.length: 0}</span>
        </div>
    );
};

export default FavoriteMatches;