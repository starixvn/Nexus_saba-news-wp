import React from 'react';
import Match from '../../../index/components/match-grid/Match';
const GridItem = ({ items }) => {
    return (
        <div className="soccer-match-grid-item">
            {
                items.map(group => {
                    return (
                        <div key={group.key}>
                            <div className="soccer-match-grid-item-header">{group.text}</div>
                            <div className="soccer-match-grid-item-body">
                                {
                                    group.childs.map(match =>
                                        <Match key={match.matchId} searchKey='' match={match} />)
                                }
                            </div>
                        </div>
                    );
                })

            }
        </div>
    );
}


export default GridItem;