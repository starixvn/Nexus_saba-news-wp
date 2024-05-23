import React from 'react';
import DateTimeService from '@jsroot/common/dateTimeService';
const Players = ({ data }) => {
    if(!data) return null
    return (
        <>
            {
                data.map((item,index) => (
                    <div className="player-group" key={index}>
                        <div className="player-title">
                            {item.position}
                        </div>
                        <div className="player-main">
                            {
                                item.players.map(item => (
                                    <div key={item.playerId} className="player-items">
                                        <div className="player-header">
                                            <div className="number">{item.shirtNumber}</div>
                                            <div className="name">{item.playerName}</div>
                                            <div className="avatar">
                                                {
                                                    item.icon && <img src={item.icon} alt={item.playerName} />
                                                }
                                            </div>
                                        </div>
                                        <div className="player-body">
                                            <div className="position">
                                                <span>Vị trí</span>
                                                <span>Hậu vệ</span>
                                            </div>
                                            <div className="birthday">
                                                <span>Ngày sinh</span>
                                                <span>{DateTimeService.convertFromUTC(item.birthday).format('DD-MM-YYYY')}</span>
                                            </div>
                                            <div className="club">
                                                <span>Câu lạc bộ</span>
                                                <span>{item.clubName}</span>
                                            </div>
                                        </div>
                                    </div>
                                ))
                            }
                        </div>
                    </div>
                ))
            }
        </>
    );
};

export default Players;