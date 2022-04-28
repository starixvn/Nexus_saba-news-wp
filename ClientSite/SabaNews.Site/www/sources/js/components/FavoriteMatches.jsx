import React from 'react';
import Site from '@jsroot/common/site';
import CommonFunctions from '@jsroot/common/common-functions';

const FavoriteMatches = ({isMobile}) => {
    const handleClick = event => {
        const url = Site.resolveClientUrl(`lich-thi-dau/bong-da/tran-dau-quan-tam`);
        if (event.ctrlKey) {
            window.open(url, '_blank');
        } else {
            window.location = url;
        }
    }

    const matches = CommonFunctions.getFavouriteMatches();
    const count = matches.length > 0 ? matches.length : "";
    return (
        <div className="livescore-like" onClick={handleClick}>
            {
                    !isMobile ?
                    <>
                        <i className="icon-livescore icon-live-start" /> <span className="title">Trận đấu quan tâm</span>
                        <span className={`like-count ${count > 0 ? 'number-like' : ''}`}>{ count }</span>
                    </> :
                    <>
                        <span>Trận đấu quan tâm</span>
                        <div className="count-start">
                            {
                                count > 0 ?
                                <i className="icon-livescore icon-live-start" data-badge={ count }></i> :
                                <i className="icon-livescore icon-live-start"></i>
                            }
                        </div>
                    </>
                }
        </div>
    );
};

export default FavoriteMatches;