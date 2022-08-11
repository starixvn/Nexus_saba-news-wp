import React from 'react';
import Match from '../../../index/components/match-grid/Match';
const GridItem = ({ matches }) => {
    return (
        <div className="soccer-match-grid-item">
            {
                matches.map(match =>
                            <Match key={match.matchId} searchKey='' match={match} />)

            }
        </div>
    );
}


export default GridItem;