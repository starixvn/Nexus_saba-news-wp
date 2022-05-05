import React from 'react';
import Site from '@jsroot/common/site';

const TeamIcon = ({ className, src }) =>
    <img className={className} loading="lazy"
        src={src ? src : Site.resolveClientUrl('css/images/logo-dummy.png')}
    />;

export default TeamIcon;