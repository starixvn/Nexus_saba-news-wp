import React from 'react';
import Site from '@jsroot/common/site';

const Breadscrum = ({ currentPage }) => {
    const handleClick = event => {
        const url = Site.resolveClientUrl(`lich-thi-dau/bong-da`);
        if (event.ctrlKey) {
            window.open(url, '_blank');
        } else {
            window.location = url;
        }
    };

    return (
        <ul className="breadcrumb">
            <li onClick={event => handleClick(event)}><span>LTĐ</span></li>
            <li className="current"><span>{currentPage}</span></li>
        </ul>
    );
};

export default Breadscrum;