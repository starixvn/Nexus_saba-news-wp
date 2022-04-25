import React from 'react';
import Skeleton from 'react-loading-skeleton';

const SkeletonMatch = () => {
    return (
        <div className="group-match-item">
            <div className="soccer-match-item">
                <div className="soccer-match-stage"><Skeleton /></div>
                <div className="soccer-match-team">
                    <div className="group-team">
                        <div className="soccer-team home-team">
                            <div className="soccer-match-logo"><Skeleton /></div>
                            <div className="soccer-match-name"><Skeleton /></div>
                            <div className="soccer-match-status"><Skeleton /></div>
                            <div className="soccer-match-result"><Skeleton /></div>
                        </div>
                        <div className="soccer-team away-team">
                            <div className="soccer-match-logo"><Skeleton /></div>
                            <div className="soccer-match-name"><Skeleton /></div>
                            <div className="soccer-match-status"><Skeleton /></div>
                            <div className="soccer-match-result"><Skeleton /></div>
                        </div>
                    </div>
                </div>
                <div className="soccer-match-star-icon">
                    <i className="icon-livescore icon-live-right" />
                </div>
            </div>
        </div>
    );
};
export default SkeletonMatch;