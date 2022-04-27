import React from 'react';
import Site from '@jsroot/common/site';

const Icon = ({ className, src }) => {
    return <img className={className} loading="lazy" src={src ? src : Site.resolveClientUrl(`css/images/logo-dummy.png`)} />;
}

export default Icon;