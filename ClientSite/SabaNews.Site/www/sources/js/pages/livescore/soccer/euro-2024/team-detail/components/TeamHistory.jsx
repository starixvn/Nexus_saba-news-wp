import React from 'react';
const TeamHistory = ({ data }) => {
    console.log(data)
    return (
        <div className="team-history">
            <div className="team-history-titlte">{data.postTitle}</div>
            <div className="team-history-description">{data.postExcerpt}</div>
            <div className="team-history-image">
                <img src={data.featureImage} alt={data.postTitle} />
            </div>
            <div className="team-history-content content" dangerouslySetInnerHTML={{ __html : data.postContent}}></div>
        </div>
    );
};

export default TeamHistory;