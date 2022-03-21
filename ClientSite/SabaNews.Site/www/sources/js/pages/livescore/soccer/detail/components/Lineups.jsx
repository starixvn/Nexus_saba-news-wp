import React, { Component } from 'react';

export default class Lineups extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        const homePlayers = [
            [{ number: 24, name: 'Frei', isGoalKeeper: true }],
            [
                { number: 16, name: 'Roldan', isGoalKeeper: false }, { number: 28, name: 'Gomez', isGoalKeeper: false },
                { number: 3, name: 'Arreaga', isGoalKeeper: false }, { number: 5, name: 'Tolo', isGoalKeeper: false }
            ],
            [
                { number: 7, name: 'Roldan', isGoalKeeper: false }, { number: 73, name: 'Vargas', isGoalKeeper: false },
                { number: 11, name: 'Rusnak', isGoalKeeper: false }, { number: 10, name: 'Lodeiro', isGoalKeeper: false },
                { number: 13, name: 'Morris', isGoalKeeper: false }
            ],
            [{ number: 9, name: 'Ruidiaz', isGoalKeeper: false }]
        ];
        const awayPlayers = [
            [{ number: 19, name: 'Rougier', isGoalKeeper: true }],
            [
                { number: 35, name: 'Melendez', isGoalKeeper: false }, { number: 5, name: 'Pereira', isGoalKeeper: false },
                { number: 2, name: 'Maldonado', isGoalKeeper: false }, { number: 17, name: 'Decas', isGoalKeeper: false }
            ],
            [
                { number: 7, name: 'Roldan', isGoalKeeper: false }, { number: 73, name: 'Vargas', isGoalKeeper: false },
                { number: 11, name: 'Rusnak', isGoalKeeper: false }, { number: 10, name: 'Lodeiro', isGoalKeeper: false }
            ],
            [{ number: 9, name: 'Ruidiaz', isGoalKeeper: false }, { number: 13, name: 'Morris', isGoalKeeper: false }]
        ];
        return (
            <>
                <div className="lineups-header lineups-title">
                    <span className="lineups-header-part">4 - 5 - 1</span>
                    <span className="lineups-header-title">Sơ đồ chiến thuật</span>
                    <span className="lineups-header-part">4 - 4 - 2</span>
                </div>
                <div className="lineups-field-wrap">
                    <div className="lineups-field">
                        <div className="lineups-field-formation lineups-field-formation-dense">
                            {
                                homePlayers.map((line, lindex) => {
                                    return (
                                        <div key={lindex} className="lineups-field-line">
                                            {
                                                line.map((player, pindex) => {
                                                    return (
                                                        <div key={pindex} className="lineups-field-player">
                                                            <div className="lineups-field-participant-incidents" />
                                                            <div className={`lineups-field-player-jersey ${player.isGoalKeeper ? 'lineups-field-goalkeeper' : ''}`}>
                                                                {player.number}
                                                            </div>
                                                            <a className="lineups-field-player-name">
                                                                <span className="lineups-field-player-name-inner">{player.name}</span>
                                                            </a>
                                                        </div>
                                                    );
                                                })
                                            }
                                        </div>
                                    );
                                })
                            }
                        </div>
                        <div className="lineups-field-formation lineups-field-formation-away lineups-field-formation-dense">
                            {
                                awayPlayers.map((line, lindex) => {
                                    return (
                                        <div key={lindex} className="lineups-field-line">
                                            {
                                                line.map((player, pindex) => {
                                                    return (
                                                        <div key={pindex} className="lineups-field-player">
                                                            <div className="lineups-field-participant-incidents" />
                                                            <div className={`lineups-field-player-jersey ${player.isGoalKeeper ? 'lineups-field-goalkeeper' : ''}`}>
                                                                {player.number}
                                                            </div>
                                                            <a className="lineups-field-player-name">
                                                                <span className="lineups-field-player-name-inner">{player.name}</span>
                                                            </a>
                                                        </div>
                                                    );
                                                })
                                            }
                                        </div>
                                    );
                                })
                            }
                        </div>
                    </div>
                </div>
                <div className="field-lineup">
                    <div className="section">
                        <div className="section-title">Đội hình ra sân</div>
                        <div className="sides-box">
                            <div className="sides">
                                <div className="side">
                                    <div className="participant">
                                        <div className="participant-number">3</div>
                                        <div className="participant-name">Arreaga X.</div>
                                    </div>
                                </div>
                                <div className="side">
                                    <div className="participant reversed">
                                        <div className="participant-number">31</div>
                                        <div className="participant-name">Auzqui D.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="section">
                        <div className="section-title">Dự bị</div>
                    </div>
                </div>
            </>
        );
    }
}