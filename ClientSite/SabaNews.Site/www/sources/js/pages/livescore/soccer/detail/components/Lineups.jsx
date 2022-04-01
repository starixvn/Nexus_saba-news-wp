import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import { MatchStatus, IncidentStatus, IncidentPosition } from '@jsroot/common/constants';
import  IncidentIcon from '@jsroot/components/Icon/IncidentIcon'

export class Lineups extends Component {
    constructor(props) {
        super(props);
    }
    componentDidMount() {
        this.props.getLineupDetail();
    }

    getPlayerEvent = (incidents, player) => {
        if(incidents === null) {
            return (
                <></>
            )
        } 
        var result = "";
        var goals  = 0;
        var ownGoals  = 0;
        var penalty  = 0;
        var redCard = 0;
        var yellowCard = 0
        var yellowTurnRedCard = 0;
        var OutSubsitution = incidents.filter(item => item.type ===  IncidentStatus.Substitution && item.outPlayerId === player.playerId).length;
        var InSubsitution = incidents.filter(item => item.type ===  IncidentStatus.Substitution && item.inPlayerId === player.playerId).length;

        incidents.map(item => {
            if(item.playerId === player.playerId) {
                switch (item.type) {
                    case IncidentStatus.Goal:
                        goals++;
                        break;
                    case IncidentStatus.OwnGoal:
                        ownGoals++;
                        break
                    case IncidentStatus.Penalty:
                        if(item.homeScore !== 0 || item.awayScore !== 0) {
                            penalty++;
                        }
                        break;
                    case IncidentStatus.RedCard:
                        redCard++;
                        break;
                    case IncidentStatus.YellowCard:
                        yellowCard++;
                        break;
                    case IncidentStatus.YellowTurnRedCard:
                        yellowTurnRedCard++
                        break
                }
            }
        })

        if(goals > 0) {
            result  += `<span> ${goals} goal</span>`;
        }
        if(ownGoals > 0) {
            result  += `<span> ${ownGoals} own</span>`;
        }
        if(penalty > 0) {
            result  +=  `<span> ${penalty} pen</span>`;
        }
        if(redCard > 0 && yellowTurnRedCard === 0) {
            result +=  `<span class="ic-red-card"></span>`;
        } else if (yellowTurnRedCard > 0) {
            result += `<span class="ic-red-card"></span>`;
        } else if (yellowCard >0) {
            result += `<span class="ic-yellow-card"></span>`;
        }
        if(OutSubsitution > 0) {
            result += `<svg class="ic ic-arrow arrowdownred" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                            <path d="M374.6 310.6l-160 160C208.4 476.9 200.2 480 192 480s-16.38-3.125-22.62-9.375l-160-160c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L160 370.8V64c0-17.69 14.33-31.1 31.1-31.1S224 46.31 224 64v306.8l105.4-105.4c12.5-12.5 32.75-12.5 45.25 0S387.1 298.1 374.6 310.6z"/>
                        </svg>`;
        }
        if(InSubsitution > 0) {
            result += `<svg class="ic ic-arrow arrowupgreen" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                        <path d="M34.9 289.5l-22.2-22.2c-9.4-9.4-9.4-24.6 0-33.9L207 39c9.4-9.4 24.6-9.4 33.9 0l194.3 194.3c9.4 9.4 9.4 24.6 0 33.9L413 289.4c-9.5 9.5-25 9.3-34.3-.4L264 168.6V456c0 13.3-10.7 24-24 24h-32c-13.3 0-24-10.7-24-24V168.6L69.2 289.1c-9.3 9.8-24.8 10-34.3.4z"/>
                    </svg>`;
        }
        return (result);
    }

    getListLineup = (lineup, teamType) => {
        let formation;
        let players;
        if(teamType === IncidentPosition.Home) {
            formation = lineup.generalInfo.homeFormation;
            players = lineup.home.filter(player => player.position !== 0);
        } else {
            formation = lineup.generalInfo.awayFormation;
            players = lineup.away.filter(player => player.position !== 0);
        }
        const formationArray = formation.split("-");
        let playersInfo = [[{number: players[0].playerNumber, name: players[0].playerName, isGoalKeeper: true}]];
        let index = 1;
        formationArray.map((line) => {
            let lineArray = [];
            for(let i = 0; i < line; i++) {
                lineArray.push({number: players[index].playerNumber, name: players[index].playerName, isGoalKeeper: false});
                index++;
            }
            playersInfo.push(lineArray);
        });
        return playersInfo;

    }

    returnListPlayer = (home, away, incidents) => {
        return (
            <div className="lineup-table">
                <table className="table-paticipant">
                    <thead>
                        <tr>
                            <th>Số áo</th>
                            <th>tên cầu thủ</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        {
                            home.map(player => {
                                return (
                                    <tr key={player.playerId}>
                                        <td>{player.playerNumber}</td>
                                        <td>{player.playerName}</td>
                                        <td><div dangerouslySetInnerHTML={{ __html: this.getPlayerEvent(incidents, player) }} /></td>
                                    </tr>
                                )
                            })
                        }
                    </tbody>
                </table>
                <table className="table-paticipant away">
                    <thead>
                        <tr>
                            <th>Số áo</th>
                            <th>tên cầu thủ</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        {
                            away.map(player => {
                                return (
                                    <tr key={player.playerId}>
                                        <td>{player.playerNumber}</td>
                                        <td>{player.playerName}</td>
                                        <td><div dangerouslySetInnerHTML={{ __html: this.getPlayerEvent(incidents, player) }} /></td>
                                    </tr>
                                )
                            })
                        }
                    </tbody>
                </table>
            </div>
        )
        
    }


    render() {
        if (! this.props.lineup || !this.props.lineup.generalInfo) {
            return null;
        }
        const lineup = this.props.lineup;
        const incidents = this.props.soccerMatch.incidents;

        const homePlayers = lineup.home.filter(player => player.position !== 0);
        const awayPlayers = lineup.away.filter(player => player.position !== 0);

        const homeSubPlayers = lineup.home.filter(player => player.position === 0);
        const awaySubPlayers = lineup.away.filter(player => player.position === 0);

        const homeFormation = lineup.generalInfo.homeFormation;
        const awayFormation = lineup.generalInfo.awayFormation;

        let homeLineup = this.getListLineup(lineup, IncidentPosition.Home);
        let awayLineup = this.getListLineup(lineup, IncidentPosition.Away);

        const homeSubstitution = incidents.filter(item => item.type ===  IncidentStatus.Substitution && item.position === IncidentPosition.Home);
        const awaySubstitution = incidents.filter(item => item.type ===  IncidentStatus.Substitution && item.position === IncidentPosition.Away);
        return (
            <>
                <div className="lineups-header lineups-title">
                    <span className="lineups-header-part">{ homeFormation }</span>
                    <span className="lineups-header-title">Sơ đồ chiến thuật</span>
                    <span className="lineups-header-part">{ awayFormation }</span>
                </div>
                <div className="lineups-field-wrap">
                    <div className="lineups-field">
                        <div className="lineups-field-formation lineups-field-formation-dense">
                            {
                                homeLineup.map((line, lindex) => {
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
                                awayLineup.map((line, lindex) => {
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
                        {this.returnListPlayer(homePlayers, awayPlayers, incidents)}
                    </div>
                    <div className="section">
                        <div className="section-title">Dự bị</div>
                        {this.returnListPlayer(homeSubPlayers, awaySubPlayers, incidents)}
                    </div>

                    <div className="section">
                        <div className="section-title">Thay người</div>
                        <div className="lineup-table">
                            <table className="table-paticipant">
                                <thead>
                                    <tr>
                                        <th>Số phút</th>
                                        <th>vào sân</th>
                                        <th>ra sân</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    {
                                        homeSubstitution.map((incident, key) => {

                                            return (
                                                <tr key={key}>
                                                    <td>{incident.time}</td>
                                                    <td>{incident.inPlayerName}</td>
                                                    <td>{incident.outPlayerName}</td>
                                                </tr>
                                            )
                                        })
                                    }
                                </tbody>
                            </table>
                            <table className="table-paticipant away">
                                <thead>
                                    <tr>
                                        <th>Số phút</th>
                                        <th>vào sân</th>
                                        <th>ra sân</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    {
                                        awaySubstitution.map((incident, key) => {

                                            return (
                                                <tr key={key}>
                                                    <td>{incident.time}</td>
                                                    <td>{incident.inPlayerName}</td>
                                                    <td>{incident.outPlayerName}</td>
                                                </tr>
                                            )
                                        })
                                    }
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </>
        );
    }
}
const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { lineup, soccerMatch } = state;
    return {
        lineup, soccerMatch
    };
};

export default connect(mapStateToProps, mapDispatchToProps)(Lineups);