import React from 'react';
const TeamHistory = ({ data }) => {
    console.log(data)
    return (
        <div className="team-history">
            <div className="team-history-image">
                <img src={data.featureImage} alt={data.postTitle} />
            </div>
            <div className="group-history">
                <div className="team-history-titlte">{data.postTitle}</div>
                <div className="team-history-description">
                    {data.postExcerpt}
                </div>
                <div className="team-history-date">{data.postTime}</div>
            </div>
        </div>
    );
};

export default TeamHistory;