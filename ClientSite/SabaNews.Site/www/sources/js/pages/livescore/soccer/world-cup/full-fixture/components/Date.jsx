import React from 'react';
import GridItem from './GridItem';
const Date = ({ dates }) => {
    return (
        <div>
            {
                dates.map((group, index) => {
                    return (
                        <div key={group.text}>
                            <div className="soccer-match-grid">{group.text}</div>
                            <GridItem matches={group.childs} />
                        </div>
                    )
                })

            }
        </div>
    );
}


export default Date;