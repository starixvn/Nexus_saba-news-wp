import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';

export class Lineups extends Component {
    constructor(props) {
        super(props);
    }
    componentDidMount() {
        this.props.getLineupDetail();
    }

    render() {
        if (! this.props.lineup) {
            return null;
        }
        const lineup = this.props.lineup;
        const homePlayers = lineup.home.filter(player => player.position !== 0);
        const awayPlayers = lineup.away.filter(player => player.position !== 0);
        const homeSubPlayers = lineup.home.filter(player => player.position === 0);
        const awaySubPlayers = lineup.away.filter(player => player.position === 0);
        const homeInfo = lineup.generalInfo.homeFormation;
        const awayInfo = lineup.generalInfo.awayFormation;

        const homeInfoArray = homeInfo.split("-");
        let homePlayersInfo = [[{number: homePlayers[0].playerNumber, name: homePlayers[0].playerName, isGoalKeeper: true}]];
        let indexHome = 1;
        homeInfoArray.map((line) => {
            let lineArray = [];
            for(let i = 0; i < line; i++) {
                lineArray.push({number: homePlayers[indexHome].playerNumber, name: homePlayers[indexHome].playerName, isGoalKeeper: false});
                indexHome++;
            }
            homePlayersInfo.push(lineArray);
        });

        const awayInfoArray = awayInfo.split("-");
        let awayPlayersInfo = [[{number: awayPlayers[0].playerNumber, name: awayPlayers[0].playerName, isGoalKeeper: true}]];
        let indexAway = 1;
        awayInfoArray.map((line) => {
            let lineArray = [];
            for(let i = 0; i < line; i++) {
                lineArray.push({number: awayPlayers[indexAway].playerNumber, name: awayPlayers[indexAway].playerName, isGoalKeeper: false});
                indexAway++;
            }
            awayPlayersInfo.push(lineArray);
        });

        // const homePlayers = [
        //     [{ number: 24, name: 'Frei', isGoalKeeper: true }],
        //     [
        //         { number: 16, name: 'Roldan', isGoalKeeper: false }, { number: 28, name: 'Gomez', isGoalKeeper: false },
        //         { number: 3, name: 'Arreaga', isGoalKeeper: false }, { number: 5, name: 'Tolo', isGoalKeeper: false }
        //     ],
        //     [
        //         { number: 7, name: 'Roldan', isGoalKeeper: false }, { number: 73, name: 'Vargas', isGoalKeeper: false },
        //         { number: 11, name: 'Rusnak', isGoalKeeper: false }, { number: 10, name: 'Lodeiro', isGoalKeeper: false },
        //         { number: 13, name: 'Morris', isGoalKeeper: false }
        //     ],
        //     [{ number: 9, name: 'Ruidiaz', isGoalKeeper: false }],
        // ];
        // const awayPlayers = [
        //     [{ number: 19, name: 'Rougier', isGoalKeeper: true }],
        //     [
        //         { number: 35, name: 'Melendez', isGoalKeeper: false }, { number: 5, name: 'Pereira', isGoalKeeper: false },
        //         { number: 2, name: 'Maldonado', isGoalKeeper: false }, { number: 17, name: 'Decas', isGoalKeeper: false }
        //     ],
        //     [
        //         { number: 7, name: 'Roldan', isGoalKeeper: false }, { number: 73, name: 'Vargas', isGoalKeeper: false },
        //         { number: 11, name: 'Rusnak', isGoalKeeper: false }, { number: 10, name: 'Lodeiro', isGoalKeeper: false }
        //     ],
        //     [{ number: 9, name: 'Ruidiaz', isGoalKeeper: false }, { number: 13, name: 'Morris', isGoalKeeper: false }]
        // ];
        return (
            <>
                <div className="lineups-header lineups-title">
                    <span className="lineups-header-part">{ homeInfo }</span>
                    <span className="lineups-header-title">Sơ đồ chiến thuật</span>
                    <span className="lineups-header-part">{ awayInfo }</span>
                </div>
                <div className="lineups-field-wrap">
                    <div className="lineups-field">
                        <div className="lineups-field-formation lineups-field-formation-dense">
                            {
                                homePlayersInfo.map((line, lindex) => {
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
                                awayPlayersInfo.map((line, lindex) => {
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
                            {
                                homePlayers.map((homePlayer, i) => {
                                    return (
                                        <div className="sides" key={i}>
                                            <div className="side">
                                                <div className="participant">
                                                    <div className="participant-number">{homePlayer.playerNumber}.</div>
                                                    <div className="participant-name">{homePlayer.playerName}</div>
                                                </div>
                                            </div>
                                        
                                            <div className="side">
                                                <div className="participant reversed">
                                                    <div className="participant-name">{awayPlayers[i].playerName}</div>
                                                    <div className="participant-number">{awayPlayers[i].playerNumber}.</div>
                                                </div>
                                            </div>
                                      
                                    </div>
                                    )
                                })
                            }
                           
                        </div>
                    </div>
                    <div className="section">
                        <div className="section-title">Dự bị</div>
                        <div className="sides-box">
                            {
                                homeSubPlayers.map((homePlayer, i) => {
                                    return (
                                        <div className="sides" key={i}>
                                            <div className="side">
                                                <div className="participant">
                                                    <div className="participant-number">{homePlayer.playerNumber}.</div>
                                                    <div className="participant-name">{homePlayer.playerName}</div>
                                                </div>
                                            </div>
                                        
                                            {/* <div className="side">
                                                <div className="participant reversed">
                                                    <div className="participant-name">{awaySubPlayers[i].playerName}</div>
                                                    <div className="participant-number">{awaySubPlayers[i].playerNumber}.</div>
                                                </div>
                                            </div> */}
                                      
                                    </div>
                                    )
                                })
                            }
                           
                        </div>
                    </div>
                </div>
            </>
        );
    }
}
const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { lineup } = state;
    return {
        lineup
    };
};

export default connect(mapStateToProps, mapDispatchToProps)(Lineups);