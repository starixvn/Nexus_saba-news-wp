import React from 'react';
import Item from './Item';
const GridItem = ({ items }) => {
    return (
        <div className="soccer-match-grid">
            {
                items.map(group => {
                    return (
                        <div key={group.key} className="soccer-match-grid-main">
                            <div className="soccer-match-grid-date">
                                {group.text}
                            </div>
                            <div className="soccer-match-grid-body">
                                {
                                    group.childs.map(match =>
                                        <Item key={match.matchId} match={match} />)
                                }
                            </div>
                        </div>
                    );
                })

            }
        </div>
    );
};


export default GridItem;