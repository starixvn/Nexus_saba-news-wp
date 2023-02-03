import React from 'react';
import Row from '../../../../league-detail/components/standings/grid/Row';

const Body = ({ teams }) => {
    return (
        <div className="table-custom-body">
            {
                teams.map(team => <Row key={team.teamId} team={team} isMobile={true} />)
            }
        </div>
    );
};

export default Body;