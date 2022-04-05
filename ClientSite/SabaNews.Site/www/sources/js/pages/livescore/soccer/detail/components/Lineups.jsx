import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import { MatchStatus, IncidentStatus, IncidentPosition } from '@jsroot/common/constants';
import  IncidentIcon from '@jsroot/components/Icon/IncidentIcon'
import Site from '@jsroot/common/site';

export class Lineups extends Component {
    constructor(props) {
        super(props);
    }
    componentDidMount() {
        this.props.getLineupDetail();
    }

    getIncident = playerId => {
        const incidents = this.props.soccerMatch.incidents;
        let playerIncident = {
            goals: 0,
            ownGoals: 0,
            penalty: 0,
            redCard: 0,
            yellowCard: 0,
            yellowTurnRedCard: 0,
            outSubsitution: 0,
            inSubsitution: 0
        }

        incidents.map(item => {
            if(item.playerId === playerId) {
                switch (item.type) {
                    case IncidentStatus.Goal:
                        playerIncident.goals++;
                        break;
                    case IncidentStatus.OwnGoal:
                        playerIncident.ownGoals++;
                        break
                    case IncidentStatus.Penalty:
                        if(item.homeScore !== 0 || item.awayScore !== 0) {
                            playerIncident.penalty++;
                        }
                        break;
                    case IncidentStatus.RedCard:
                        playerIncident.redCard++;
                        break;
                    case IncidentStatus.YellowCard:
                        playerIncident.yellowCard++;
                        break;
                    case IncidentStatus.YellowTurnRedCard:
                        playerIncident.yellowTurnRedCard++
                        break
                }
            }
            if(item.type === IncidentStatus.Substitution) {
                if(item.outPlayerId === playerId) {
                    playerIncident.outSubsitution++
                } 
                if(item.inPlayerId === playerId) {
                    playerIncident.inSubsitution++
                } 
            }
        });
        return playerIncident;
    }

    renderPlayerIncident = player => {
        var result = "";
        var playerIncident = this.getIncident(player.playerId);

        if(playerIncident.goals > 0) {
            result  += `<span> ${playerIncident.goals} goal</span>`;
        }
        if(playerIncident.ownGoals > 0) {
            result  += `<span> ${playerIncident.ownGoals} own</span>`;
        }
        if(playerIncident.penalty > 0) {
            result  +=  `<span> ${playerIncident.penalty} pen</span>`;
        }
        if(playerIncident.redCard > 0 && playerIncident.yellowTurnRedCard === 0) {
            result +=  `<span class="ic-red-card"></span>`;
        } else if (playerIncident.yellowTurnRedCard > 0) {
            result += `<span class="ic-red-card"></span>`;
        } else if (playerIncident.yellowCard >0) {
            result += `<span class="ic-yellow-card"></span>`;
        }
        if(playerIncident.outSubsitution > 0) {
            result += `<svg class="ic ic-arrow arrowdownred" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                            <path d="M374.6 310.6l-160 160C208.4 476.9 200.2 480 192 480s-16.38-3.125-22.62-9.375l-160-160c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L160 370.8V64c0-17.69 14.33-31.1 31.1-31.1S224 46.31 224 64v306.8l105.4-105.4c12.5-12.5 32.75-12.5 45.25 0S387.1 298.1 374.6 310.6z"/>
                        </svg>`;
        }
        if(playerIncident.inSubsitution > 0) {
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
                let incident = this.getIncident(players[index].playerId)
                lineArray.push({
                    number: players[index].playerNumber, 
                    name: players[index].playerName, 
                    isGoalKeeper: false, goals: incident.goals, 
                    ownGoals: incident.ownGoals,
                    penalty: incident.penalty,
                    outSubsitution: incident.outSubsitution,
                    inSubsitution: incident.inSubsitution,
                    redCard: incident.redCard,
                    yellowCard: incident.yellowCard,
                    yellowTurnRedCard: incident.yellowTurnRedCard
                });
                index++;
            }
            playersInfo.push(lineArray);
        });
        return playersInfo;

    }

    returnListPlayer = (home, away) => {
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
                                        <td><div dangerouslySetInnerHTML={{ __html: this.renderPlayerIncident(player) }} /></td>
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
                                        <td><div dangerouslySetInnerHTML={{ __html: this.renderPlayerIncident(player) }} /></td>
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
        console.log(lineup);
        const generalInfo = this.props.soccerMatch.generalInfo;
        const incidents = this.props.soccerMatch.incidents;

        const homePlayers = lineup.home.filter(player => player.position !== 0);
        const awayPlayers = lineup.away.filter(player => player.position !== 0);

        const homeSubPlayers = lineup.home.filter(player => player.position === 0);
        const awaySubPlayers = lineup.away.filter(player => player.position === 0);

        const homeFormation = lineup.generalInfo.homeFormation;
        const awayFormation = lineup.generalInfo.awayFormation;

        let homeLineup = this.getListLineup(lineup, IncidentPosition.Home);
        let awayLineup = this.getListLineup(lineup, IncidentPosition.Away);
        let awayLineupReverse = awayLineup.reverse();

        const homeSubstitution = incidents.filter(item => item.type ===  IncidentStatus.Substitution && item.position === IncidentPosition.Home);
        const awaySubstitution = incidents.filter(item => item.type ===  IncidentStatus.Substitution && item.position === IncidentPosition.Away);
        return (
            <>
                <div className="lineup">
                    <div className="lineup-head">
                        <div className="team-name">
                            <img className="club-logo-img" src={generalInfo.homeTeamIcon} alt="" />
                            <div className="club-logo-text">{generalInfo.homeTeamNameEN}</div>
                        </div>
                        <div className="f-right-text">{ homeFormation }</div>
                    </div>
                    <div className="team-body">
                        <div className="team-content">
                            {
                                homeLineup.map((line, lineIndex) => {
                                    return (
                                        <div className="pitch-row" key={lineIndex}>
                                            {
                                                    line.map((player, pindex) => {
                                                        return (
                                                            <div className="pitch-item" key={pindex}>
                                                                <div className="team-player-img">
                                                                    <div className="team-number">{player.number}</div>
                                                                    <img src={Site.resolveClientUrl(`css/images/dress-white.gif`)} alt="" />
                                                                </div>
                                                                <div className="text-name">{player.name}</div>
                                                            </div>
                                                        )
                                                    })
                                            }
                                        </div>
                                    )
                                })
                            }
                        </div>
                        <div className="team-content">
                        {
                                
                                awayLineupReverse.map((line, lineIndex) => {
                                    return (
                                        <div className="pitch-row" key={lineIndex}>
                                            {
                                                    line.map((player, pindex) => {
                                                        return (
                                                            <div className="pitch-item" key={pindex}>
                                                                <div className="team-player-img">
                                                                    <img src={Site.resolveClientUrl(`css/images/dress-blue.gif`)} alt="" />
                                                                    <div className="team-number">
                                                                        {player.number}
                                                                        <span className="icon_f ic_bong">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" name="FootballGoal"><g transform="translate(.63)" fill="none" fill-rule="evenodd"><circle cx="7" cy="7" r="6" fill="#FDFDFD"></circle><path fill="#222" d="M7 0h.28H7a6.8 6.8 0 01.56.02l.14.01.25.03h.02l.04.01.22.04.13.02.14.03.13.03.24.06.08.03.23.07.04.01.53.2.03.01c.95.42 1.8 1.03 2.47 1.8a5.96 5.96 0 01.62.82l.08.13.06.1c.42.7.72 1.49.87 2.32l.02.07a7.47 7.47 0 01.07 1.85 6.97 6.97 0 01-.94 2.9l-.03.05-.08.13.1-.18-.2.34-.03.04-.37.5-.04.03c-.12.16-.26.3-.4.45l-.05.05-.12.12-.4.35-.07.05-.19.15-.04.02v.01l-.25.17.25-.18a7.3 7.3 0 01-1.67.9l-.05.02-.22.08-.06.02a5.9 5.9 0 01-.37.1l-.16.04a6.95 6.95 0 01-3.11.01l-.06-.01-.15-.04-.09-.02-.03-.01a6.16 6.16 0 01-.24-.07l-.09-.03-.2-.07-.06-.02a7.96 7.96 0 01-.24-.1h-.03c-.5-.22-.96-.48-1.38-.79h-.01l-.04-.03-.2-.16.24.18a6.66 6.66 0 01-.82-.7l-.05-.04a6.47 6.47 0 01-.4-.45l-.04-.04A6.97 6.97 0 01.03 7.66a7.5 7.5 0 010-1.34l.02-.13.01-.11.04-.27.02-.11c.16-.82.45-1.59.87-2.28l.06-.1.08-.13A6.94 6.94 0 014.22.58l.04-.02.51-.2.06-.01.23-.08.06-.01.25-.07c.05 0 .09-.02.13-.03l.14-.03.13-.02L6 .07h.06L6.3.03h.14A3.85 3.85 0 017 0zm1.88 1.3L7.44 2.45a.7.7 0 01-.8.05l-.08-.05L5.12 1.3a6 6 0 00-2.96 2.16l.65 1.72a.7.7 0 01-.2.78L2.54 6 1 7.02v.2a5.96 5.96 0 001.14 3.29l1.83-.09a.7.7 0 01.68.43l.03.09.49 1.77a5.94 5.94 0 003.66 0l.49-1.77a.7.7 0 01.62-.51h.09l1.84.08A5.96 5.96 0 0013 7.02l-1.54-1.01a.7.7 0 01-.3-.75l.03-.08.65-1.72A6.01 6.01 0 008.88 1.3zM7.4 4.5l1.84 1.33c.24.18.35.5.25.79l-.7 2.16a.7.7 0 01-.66.48H5.86a.7.7 0 01-.66-.48l-.7-2.16a.7.7 0 01.25-.78L6.59 4.5a.7.7 0 01.82 0z"></path></g></svg>
                                                                        </span>
                                                                        <span class="icon_f ic_thay_nguoi">
                                                                            <svg viewBox="0 0 14 14" fill="red" name="FootballSubOut">
                                                                                <path fill="#df2357" d="M14 7A7 7 0 110 7a7 7 0 0114 0z"></path>
                                                                                <path fill="#0b0b0b" d="M9 3.5V7h1.44a.25.25 0 01.19.42l-3.26 3.66a.5.5 0 01-.74 0L3.37 7.42A.25.25 0 013.56 7H5V3.5c0-.28.22-.5.5-.5h3c.28 0 .5.22.5.5z"></path>
                                                                            </svg>
                                                                        </span>
                                                                        <span class="icon_f ic_the_vang">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="14" name="FootballYellowCard"><rect width="10" height="14" x="2" rx="2" transform="translate(-1.68)" fill="#FFCE00" fill-rule="evenodd"></rect></svg>
                                                                        </span>
                                                                        <span class="icon_f ic_the_do">
                                                                            <span class="Common_iconWrapper__3p9Oo">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="14" name="FootballRedCard"><rect width="10" height="14" x="2" rx="2" transform="translate(-1.66)" fill="#DF2357" fill-rule="evenodd"></rect></svg>
                                                                            </span>
                                                                        </span>
                                                                        <span className="icon_f ic_penalty">
                                                                            <span>P</span>
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" name="FootballGoal"><g transform="translate(.63)" fill="none" fill-rule="evenodd"><circle cx="7" cy="7" r="6" fill="#FDFDFD"></circle><path fill="#222" d="M7 0h.28H7a6.8 6.8 0 01.56.02l.14.01.25.03h.02l.04.01.22.04.13.02.14.03.13.03.24.06.08.03.23.07.04.01.53.2.03.01c.95.42 1.8 1.03 2.47 1.8a5.96 5.96 0 01.62.82l.08.13.06.1c.42.7.72 1.49.87 2.32l.02.07a7.47 7.47 0 01.07 1.85 6.97 6.97 0 01-.94 2.9l-.03.05-.08.13.1-.18-.2.34-.03.04-.37.5-.04.03c-.12.16-.26.3-.4.45l-.05.05-.12.12-.4.35-.07.05-.19.15-.04.02v.01l-.25.17.25-.18a7.3 7.3 0 01-1.67.9l-.05.02-.22.08-.06.02a5.9 5.9 0 01-.37.1l-.16.04a6.95 6.95 0 01-3.11.01l-.06-.01-.15-.04-.09-.02-.03-.01a6.16 6.16 0 01-.24-.07l-.09-.03-.2-.07-.06-.02a7.96 7.96 0 01-.24-.1h-.03c-.5-.22-.96-.48-1.38-.79h-.01l-.04-.03-.2-.16.24.18a6.66 6.66 0 01-.82-.7l-.05-.04a6.47 6.47 0 01-.4-.45l-.04-.04A6.97 6.97 0 01.03 7.66a7.5 7.5 0 010-1.34l.02-.13.01-.11.04-.27.02-.11c.16-.82.45-1.59.87-2.28l.06-.1.08-.13A6.94 6.94 0 014.22.58l.04-.02.51-.2.06-.01.23-.08.06-.01.25-.07c.05 0 .09-.02.13-.03l.14-.03.13-.02L6 .07h.06L6.3.03h.14A3.85 3.85 0 017 0zm1.88 1.3L7.44 2.45a.7.7 0 01-.8.05l-.08-.05L5.12 1.3a6 6 0 00-2.96 2.16l.65 1.72a.7.7 0 01-.2.78L2.54 6 1 7.02v.2a5.96 5.96 0 001.14 3.29l1.83-.09a.7.7 0 01.68.43l.03.09.49 1.77a5.94 5.94 0 003.66 0l.49-1.77a.7.7 0 01.62-.51h.09l1.84.08A5.96 5.96 0 0013 7.02l-1.54-1.01a.7.7 0 01-.3-.75l.03-.08.65-1.72A6.01 6.01 0 008.88 1.3zM7.4 4.5l1.84 1.33c.24.18.35.5.25.79l-.7 2.16a.7.7 0 01-.66.48H5.86a.7.7 0 01-.66-.48l-.7-2.16a.7.7 0 01.25-.78L6.59 4.5a.7.7 0 01.82 0z"></path></g></svg>
                                                                        </span>
                                                                    </div>
                                                                   
                                                                </div>
                                                                
                                                                <div className="text-name">{player.name}</div>
                                                            </div>
                                                        )
                                                    })
                                            }
                                        </div>
                                    )
                                })
                            }
                        </div>
                    </div>
                    <div className="lineup-head">
                        <div className="team-name">
                            <img className="club-logo-img" src={generalInfo.awayTeamIcon} alt="" />
                            <div className="club-logo-text">{generalInfo.awayTeamNameEN}</div>
                        </div>
                        <div className="f-right-text">{ awayFormation }</div>
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