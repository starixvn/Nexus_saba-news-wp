import React from 'react';
import Skeleton from 'react-loading-skeleton';

const SkeletonGrid = () => {
    return (
        <div className="wc-bg-overflow">
            <div className="wc-date-bar">
                {
                    [1, 2, 3, 4, 5, 6, 7, 8].map(item =>
                        <div key={item} className="group-match-item">
                            <div className="soccer-match-item"
                            >
                                <div className="soccer-match-date-result">
                                    <div className="group-live-icon">
                                        <i className="icon-livescore icon-wc-live" />
                                        <div className="soccer-match-stage"><Skeleton /></div>
                                    </div>
                                    <span className="wc-date"><Skeleton width={20} /></span>
                                </div>
                                <div className="soccer-match-team">
                                    <div className="group-team">
                                        <div className="soccer-team home-team">
                                            <Skeleton circle className="soccer-team-logo" />
                                            <Skeleton className="soccer-team-name" width={100} />
                                            <div className="soccer-team-result">
                                                <span className="total-score"><Skeleton /></span>
                                                <span className="half-score"><Skeleton /></span>
                                            </div>
                                        </div>
                                        <div className="soccer-team away-team">
                                            <Skeleton circle className="soccer-team-logo" />
                                            <Skeleton className="soccer-team-name" width={100} />
                                            <div className="soccer-team-result">
                                                <span className="total-score"><Skeleton /></span>
                                                <span className="half-score"><Skeleton /></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    )
                }
            </div>
        </div>
    );
};
export default SkeletonGrid;