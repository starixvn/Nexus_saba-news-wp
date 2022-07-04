import React from 'react';
import Site from '@jsroot/common/site';

const TeamIcon = ({ className, src, onClick }) => {
    const handleClick = event => {
        if(onClick) {
            onClick(event);
        }
    };
    return (
        <img className={className} loading="lazy" onClick={handleClick}
            src={src ? src : Site.resolveClientUrl('css/images/logo-dummy.png')}
        />
    );
};

export default TeamIcon;