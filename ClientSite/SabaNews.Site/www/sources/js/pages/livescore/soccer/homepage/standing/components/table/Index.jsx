import React from 'react';
import TableHead from './Head';
import TableBody from './Body';

const Index = ({ standings, isMobile, isCup }) => {
    const totalItems = standings.length;
    return (
        <>
            {
                standings.map((group, index) => {
                    const groupName = totalItems > 1 || isCup ? `Bảng ${group.key.charAt(0)}` : 'Đội';

                    return (
                        <div key={index} className="table-custom">
                            <TableHead groupName={groupName} isMobile={isMobile} />
                            <TableBody teams={group.childs} isMobile={isMobile} />
                        </div>
                    );
                })
            }
        </>
    );
};

export default Index;