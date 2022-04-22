import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import { IncidentStatus, IncidentPosition } from '@jsroot/common/constants';
import Site from '@jsroot/common/site';
import LangHelper from '@jsroot/common/langHelper';
import CommonFunctions from '@jsroot/common/common-functions';

export class Lineups extends Component {
    constructor(props) {
        super(props);
    }

    getPlayerRole = role => {
        switch (role) {
            case "F":
                return LangHelper.getResource('Striker');
            case "M":
                return LangHelper.getResource('Midfielder');
            case "D":
                return LangHelper.getResource('Defender');
            case "G":
                return LangHelper.getResource('Goalkeeper');
            default:
                return "";
        }
    }
    getPlayerIncident = playerId => {
        const incidents = this.props.match.incidents;
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
        if (!incidents) {
            return playerIncident;
        }

        incidents.map(item => {
            if (item.playerId === playerId) {
                switch (item.type) {
                    case IncidentStatus.Goal:
                        playerIncident.goals++;
                        break;
                    case IncidentStatus.OwnGoal:
                        playerIncident.ownGoals++;
                        break
                    case IncidentStatus.Penalty:
                        if (item.homeScore !== 0 || item.awayScore !== 0) {
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
            if (item.type === IncidentStatus.Substitution) {
                if (item.outPlayerId === playerId) {
                    playerIncident.outSubsitution++
                }
                if (item.inPlayerId === playerId) {
                    playerIncident.inSubsitution++
                }
            }
        });
        return playerIncident;
    }

    renderIconListPlayer = player => {
        var playerIncident = this.getPlayerIncident(player.playerId);
        return (
            <>
                {
                    playerIncident.goals > 0 &&
                    <span className="icon-line-up">
                        <i className="icon-livescore icon-ball" />
                    </span>
                }
                {
                    playerIncident.ownGoals > 0 &&
                    <span className="icon-line-up">
                        <svg width="31" height="24" viewBox="0 0 31 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="19" cy="12" r="12" fill="white" />
                            <path d="M26.6395 4.37039C25.6394 3.36372 24.4493 2.56518 23.138 2.02101C21.8268 1.47685 20.4205 1.19786 19.0006 1.20021C17.5803 1.19799 16.1737 1.47703 14.8622 2.02118C13.5506 2.56533 12.3601 3.3638 11.3595 4.37039C7.14677 8.57644 7.14677 15.4229 11.3595 19.63C12.3601 20.6369 13.5508 21.4355 14.8626 21.9797C16.1743 22.5238 17.5812 22.8027 19.0016 22.8002C20.4215 22.8027 21.8278 22.5239 23.139 21.9799C24.4502 21.4359 25.6404 20.6376 26.6405 19.6311C30.8533 15.425 30.8533 8.5786 26.6395 4.37039ZM25.7448 17.3953H23.3213L21.9621 20.1102C21.0139 20.46 20.0114 20.6401 19.0006 20.6421C17.9879 20.6403 16.9834 20.4598 16.0336 20.1091L14.6776 17.4061H12.2628C11.3719 16.3 10.7694 14.9906 10.5092 13.595L12.5167 10.9212L11.2028 8.29589C11.6221 7.40525 12.1919 6.59345 12.8873 5.89614C13.8606 4.92076 15.0535 4.19226 16.3664 3.77153L18.9995 5.52603L21.6337 3.77261C22.9462 4.19363 24.139 4.92168 25.1128 5.89614C25.8074 6.59262 26.3768 7.40329 26.7961 8.29266L25.4823 10.9212L27.4898 13.595C27.2308 14.9861 26.6313 16.2916 25.7448 17.3953Z" fill="#EE2316" />
                            <path d="M15.3999 11.1691L16.9428 15.5999H21.057L22.5999 11.1691L18.9999 8.3999L15.3999 11.1691Z" fill="#EE2316" />
                            <rect width="19" height="13" rx="5" fill="white" />
                            <path d="M9.58293 7.06299C9.58293 8.36279 9.26066 9.36182 8.61613 10.0601C7.9716 10.7583 7.04777 11.1074 5.84465 11.1074C4.64152 11.1074 3.7177 10.7583 3.07316 10.0601C2.42863 9.36182 2.10637 8.35921 2.10637 7.05225C2.10637 5.74528 2.42863 4.74805 3.07316 4.06055C3.72128 3.36947 4.64868 3.02393 5.85539 3.02393C7.0621 3.02393 7.98413 3.37126 8.6215 4.06592C9.26245 4.76058 9.58293 5.7596 9.58293 7.06299ZM3.85197 7.06299C3.85197 7.94027 4.01848 8.60091 4.35148 9.04492C4.68449 9.48893 5.18221 9.71094 5.84465 9.71094C7.1731 9.71094 7.83732 8.82829 7.83732 7.06299C7.83732 5.29411 7.17668 4.40967 5.85539 4.40967C5.19296 4.40967 4.69344 4.63346 4.35686 5.08105C4.02027 5.52507 3.85197 6.18571 3.85197 7.06299ZM13.5313 6.59033H16.6466V10.6616C16.1417 10.8263 15.6654 10.9409 15.2179 11.0054C14.7738 11.0734 14.3191 11.1074 13.8536 11.1074C12.6684 11.1074 11.7624 10.7601 11.1358 10.0654C10.5128 9.36719 10.2013 8.36637 10.2013 7.06299C10.2013 5.79541 10.5629 4.80713 11.2862 4.09814C12.0131 3.38916 13.0193 3.03467 14.3048 3.03467C15.1104 3.03467 15.8874 3.1958 16.6358 3.51807L16.0826 4.8501C15.5097 4.56364 14.9135 4.42041 14.294 4.42041C13.5743 4.42041 12.9978 4.66211 12.5645 5.14551C12.1313 5.62891 11.9146 6.27881 11.9146 7.09521C11.9146 7.94743 12.0883 8.59912 12.4356 9.05029C12.7865 9.49788 13.295 9.72168 13.961 9.72168C14.3083 9.72168 14.661 9.68587 15.0191 9.61426V7.97607H13.5313V6.59033Z" fill="#EE2316" />
                        </svg>
                    </span>
                }
                {
                    playerIncident.penalty > 0 &&
                    <span className="icon-line-up">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="12" fill="white" />
                            <path d="M19.6395 4.37039C18.6394 3.36372 17.4493 2.56518 16.138 2.02101C14.8268 1.47685 13.4205 1.19786 12.0006 1.20021C10.5803 1.19799 9.17373 1.47703 7.86216 2.02118C6.5506 2.56533 5.36009 3.3638 4.3595 4.37039C0.146767 8.57644 0.146767 15.4229 4.3595 19.63C5.36011 20.6369 6.55077 21.4355 7.86256 21.9797C9.17434 22.5238 10.5812 22.8027 12.0016 22.8002C13.4215 22.8027 14.8278 22.5239 16.139 21.9799C17.4502 21.4359 18.6404 20.6376 19.6405 19.6311C23.8533 15.425 23.8533 8.5786 19.6395 4.37039ZM18.7448 17.3953H16.3213L14.9621 20.1102C14.0139 20.46 13.0114 20.6401 12.0006 20.6421C10.9879 20.6403 9.98345 20.4598 9.0336 20.1091L7.67762 17.4061H5.26277C4.37186 16.3 3.76937 14.9906 3.50918 13.595L5.51668 10.9212L4.20284 8.29589C4.62206 7.40525 5.19189 6.59345 5.88728 5.89614C6.86055 4.92076 8.0535 4.19226 9.36638 3.77153L11.9995 5.52603L14.6337 3.77261C15.9462 4.19363 17.139 4.92168 18.1128 5.89614C18.8074 6.59262 19.3768 7.40329 19.7961 8.29266L18.4823 10.9212L20.4898 13.595C20.2308 14.9861 19.6313 16.2916 18.7448 17.3953Z" fill="#324A75" />
                            <path d="M8.3999 11.1691L9.94276 15.5999H14.057L15.5999 11.1691L11.9999 8.3999L8.3999 11.1691Z" fill="#324A75" />
                            <circle cx="6.5" cy="6.5" r="6.5" fill="white" />
                            <path d="M6.19971 6.84277H6.74756C7.2596 6.84277 7.64274 6.74251 7.89697 6.54199C8.1512 6.33789 8.27832 6.04248 8.27832 5.65576C8.27832 5.26546 8.1709 4.97721 7.95605 4.79102C7.74479 4.60482 7.41178 4.51172 6.95703 4.51172H6.19971V6.84277ZM9.95947 5.59668C9.95947 6.44173 9.6945 7.08805 9.16455 7.53564C8.63818 7.98324 7.88802 8.20703 6.91406 8.20703H6.19971V11H4.53467V3.14746H7.04297C7.99544 3.14746 8.71875 3.35335 9.21289 3.76514C9.71061 4.17334 9.95947 4.78385 9.95947 5.59668Z" fill="#324A75" />
                        </svg>
                    </span>
                }
                {
                    playerIncident.outSubsitution > 0 &&
                    <span className="icon-line-up">
                        <i className="icon-livescore icon-out" />
                    </span>
                }
                {
                    playerIncident.inSubsitution > 0 &&
                    <span className="icon-line-up">
                        <i className="icon-livescore icon-in" />
                    </span>
                }
                {
                    (playerIncident.redCard > 0) &&
                    <span className="icon-line-up">
                        <i className="icon-livescore icon-red-card" />
                    </span>
                }
                {
                    (playerIncident.yellowTurnRedCard > 0) &&
                    <span className="icon-line-up">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="6" y="5" width="16" height="18" rx="3" fill="#F9C91E" />
                            <rect x="2" y="1" width="16" height="19" rx="3" fill="#FF5744" />
                        </svg>
                    </span>


                }
                {
                    (playerIncident.yellowCard > 0 && playerIncident.yellowTurnRedCard === 0) &&
                    <span className="icon-line-up">
                        <i className="icon-livescore icon-yellow-card" />
                    </span>
                }
            </>
        )
    }

    getListLineup = (lineup, teamType) => {
        let formation;
        let players;
        if (teamType === IncidentPosition.Home) {
            formation = lineup.generalInfo.homeFormation;
            players = lineup.home.filter(player => player.position !== 0);
        } else {
            formation = lineup.generalInfo.awayFormation;
            players = lineup.away.filter(player => player.position !== 0);
        }
        if (players.length === 0) {
            return null;
        }
        const formationArray = formation.split("-");
        var goalKeeperIncident = this.getPlayerIncident(players[0].playerId);
        let playersInfo = [[
            {
                number: players[0].playerNumber,
                name: players[0].playerName,
                isGoalKeeper: true,
                goals: goalKeeperIncident.goals,
                ownGoals: goalKeeperIncident.ownGoals,
                penalty: goalKeeperIncident.penalty,
                outSubsitution: goalKeeperIncident.outSubsitution,
                inSubsitution: goalKeeperIncident.inSubsitution,
                redCard: goalKeeperIncident.redCard,
                yellowCard: goalKeeperIncident.yellowCard,
                yellowTurnRedCard: goalKeeperIncident.yellowTurnRedCard
            }
        ]];
        let index = 1;
        formationArray.map((line) => {
            let lineArray = [];
            for (let i = 0; i < line; i++) {
                if (!players[index]) {
                    break;
                }
                let incident = this.getPlayerIncident(players[index].playerId)
                lineArray.push({
                    number: players[index].playerNumber,
                    name: players[index].playerName,
                    isGoalKeeper: false,
                    goals: incident.goals,
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

    renderIconLineup = player => {
        return (
            <>
                {
                    player.goals > 0 &&
                    <span className="icon_f ic_bong">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" name="FootballGoal"><g transform="translate(.63)" fill="none" fillRule="evenodd"><circle cx="7" cy="7" r="6" fill="#FDFDFD"></circle><path fill="#222" d="M7 0h.28H7a6.8 6.8 0 01.56.02l.14.01.25.03h.02l.04.01.22.04.13.02.14.03.13.03.24.06.08.03.23.07.04.01.53.2.03.01c.95.42 1.8 1.03 2.47 1.8a5.96 5.96 0 01.62.82l.08.13.06.1c.42.7.72 1.49.87 2.32l.02.07a7.47 7.47 0 01.07 1.85 6.97 6.97 0 01-.94 2.9l-.03.05-.08.13.1-.18-.2.34-.03.04-.37.5-.04.03c-.12.16-.26.3-.4.45l-.05.05-.12.12-.4.35-.07.05-.19.15-.04.02v.01l-.25.17.25-.18a7.3 7.3 0 01-1.67.9l-.05.02-.22.08-.06.02a5.9 5.9 0 01-.37.1l-.16.04a6.95 6.95 0 01-3.11.01l-.06-.01-.15-.04-.09-.02-.03-.01a6.16 6.16 0 01-.24-.07l-.09-.03-.2-.07-.06-.02a7.96 7.96 0 01-.24-.1h-.03c-.5-.22-.96-.48-1.38-.79h-.01l-.04-.03-.2-.16.24.18a6.66 6.66 0 01-.82-.7l-.05-.04a6.47 6.47 0 01-.4-.45l-.04-.04A6.97 6.97 0 01.03 7.66a7.5 7.5 0 010-1.34l.02-.13.01-.11.04-.27.02-.11c.16-.82.45-1.59.87-2.28l.06-.1.08-.13A6.94 6.94 0 014.22.58l.04-.02.51-.2.06-.01.23-.08.06-.01.25-.07c.05 0 .09-.02.13-.03l.14-.03.13-.02L6 .07h.06L6.3.03h.14A3.85 3.85 0 017 0zm1.88 1.3L7.44 2.45a.7.7 0 01-.8.05l-.08-.05L5.12 1.3a6 6 0 00-2.96 2.16l.65 1.72a.7.7 0 01-.2.78L2.54 6 1 7.02v.2a5.96 5.96 0 001.14 3.29l1.83-.09a.7.7 0 01.68.43l.03.09.49 1.77a5.94 5.94 0 003.66 0l.49-1.77a.7.7 0 01.62-.51h.09l1.84.08A5.96 5.96 0 0013 7.02l-1.54-1.01a.7.7 0 01-.3-.75l.03-.08.65-1.72A6.01 6.01 0 008.88 1.3zM7.4 4.5l1.84 1.33c.24.18.35.5.25.79l-.7 2.16a.7.7 0 01-.66.48H5.86a.7.7 0 01-.66-.48l-.7-2.16a.7.7 0 01.25-.78L6.59 4.5a.7.7 0 01.82 0z"></path></g></svg>
                    </span>
                }
                {
                    player.ownGoals > 0 &&
                    <span className="icon_f ic_bong">
                        <svg className="ic ic-ball fill-red" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4.678.549A7.5,7.5,0,0,0,2.2,12.8a7.494,7.494,0,0,0,10.607,0A7.494,7.494,0,0,0,12.8,2.2,7.51,7.51,0,0,0,4.678.549Zm-2.8,10.109a5.148,5.148,0,0,1-.989-.8,7.071,7.071,0,0,1-.228-3.94,6.3,6.3,0,0,1,.48-.8c.056-.079.107-.16.167-.237L3,5.89v.042a13.038,13.038,0,0,0,.272,2.7s0,.006,0,.01L1.959,10.705C1.93,10.691,1.9,10.675,1.875,10.657Zm5.032,3.479a6.444,6.444,0,0,0,.9.377A7.034,7.034,0,0,1,4,13.592L6.84,14.1C6.862,14.112,6.884,14.125,6.907,14.137Zm-.01-.512-3.348-.6A10.455,10.455,0,0,1,2.5,11.511c-.077-.137-.163-.27-.228-.412L3.584,9.043l.1.025a21.761,21.761,0,0,0,2.5.5l.125.017L7.875,12.3q-.409.575-.831,1.142C7,13.5,6.948,13.565,6.9,13.625Zm5.57-1.158a6.977,6.977,0,0,1-.846.721,6.428,6.428,0,0,0-.253-1.153l1.677-3.129a7.835,7.835,0,0,0,1.321-.745.591.591,0,0,0,.134-.128A6.989,6.989,0,0,1,12.467,12.467Zm-.074-4.058c.062.119.124.239.186.361l-1.62,3.022-.028.005a14.776,14.776,0,0,1-2.649.255l-1.617-2.8q.377-.7.748-1.405c.168-.318.333-.639.5-.959l3.346-.344A18.677,18.677,0,0,1,12.393,8.409Zm-.679-3.584c-.159.419-.329.834-.5,1.25l-3.259.335c-.039-.051-.079-.1-.122-.15q-.577-.654-1.159-1.3l.011-.012-.469-.468.907-2.732a1.273,1.273,0,0,1,.189-.037c.493-.05.987-.107,1.482-.136a7.207,7.207,0,0,1,.915,0L11.957,4.1C11.879,4.341,11.8,4.586,11.714,4.825Zm.753-2.292a6.993,6.993,0,0,1,1.438,2.082,7.13,7.13,0,0,0-1.34-.72c-.073-.03-.149-.057-.225-.083l-2.21-2.491A2.922,2.922,0,0,0,10.157,1,6.991,6.991,0,0,1,12.467,2.533ZM5.251.867c.009.006.013.012.018.013a5.617,5.617,0,0,1,1.4.736L5.819,4.169l-.013,0c-.173.072-.35.136-.521.214a14.945,14.945,0,0,0-2.036,1.1L1.576,4.5a.506.506,0,0,1,.029-.153c.112-.3.223-.6.35-.894a6.7,6.7,0,0,1,.331-.657c.08-.088.162-.175.247-.26A6.983,6.983,0,0,1,5.266.841Z" transform="translate(0 0)"></path>
                        </svg>
                    </span>
                }
                {
                    player.penalty > 0 &&
                    <span className="icon_f ic_penalty">
                        <span>P</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" name="FootballGoal"><g transform="translate(.63)" fill="none" fillRule="evenodd"><circle cx="7" cy="7" r="6" fill="#FDFDFD"></circle><path fill="#222" d="M7 0h.28H7a6.8 6.8 0 01.56.02l.14.01.25.03h.02l.04.01.22.04.13.02.14.03.13.03.24.06.08.03.23.07.04.01.53.2.03.01c.95.42 1.8 1.03 2.47 1.8a5.96 5.96 0 01.62.82l.08.13.06.1c.42.7.72 1.49.87 2.32l.02.07a7.47 7.47 0 01.07 1.85 6.97 6.97 0 01-.94 2.9l-.03.05-.08.13.1-.18-.2.34-.03.04-.37.5-.04.03c-.12.16-.26.3-.4.45l-.05.05-.12.12-.4.35-.07.05-.19.15-.04.02v.01l-.25.17.25-.18a7.3 7.3 0 01-1.67.9l-.05.02-.22.08-.06.02a5.9 5.9 0 01-.37.1l-.16.04a6.95 6.95 0 01-3.11.01l-.06-.01-.15-.04-.09-.02-.03-.01a6.16 6.16 0 01-.24-.07l-.09-.03-.2-.07-.06-.02a7.96 7.96 0 01-.24-.1h-.03c-.5-.22-.96-.48-1.38-.79h-.01l-.04-.03-.2-.16.24.18a6.66 6.66 0 01-.82-.7l-.05-.04a6.47 6.47 0 01-.4-.45l-.04-.04A6.97 6.97 0 01.03 7.66a7.5 7.5 0 010-1.34l.02-.13.01-.11.04-.27.02-.11c.16-.82.45-1.59.87-2.28l.06-.1.08-.13A6.94 6.94 0 014.22.58l.04-.02.51-.2.06-.01.23-.08.06-.01.25-.07c.05 0 .09-.02.13-.03l.14-.03.13-.02L6 .07h.06L6.3.03h.14A3.85 3.85 0 017 0zm1.88 1.3L7.44 2.45a.7.7 0 01-.8.05l-.08-.05L5.12 1.3a6 6 0 00-2.96 2.16l.65 1.72a.7.7 0 01-.2.78L2.54 6 1 7.02v.2a5.96 5.96 0 001.14 3.29l1.83-.09a.7.7 0 01.68.43l.03.09.49 1.77a5.94 5.94 0 003.66 0l.49-1.77a.7.7 0 01.62-.51h.09l1.84.08A5.96 5.96 0 0013 7.02l-1.54-1.01a.7.7 0 01-.3-.75l.03-.08.65-1.72A6.01 6.01 0 008.88 1.3zM7.4 4.5l1.84 1.33c.24.18.35.5.25.79l-.7 2.16a.7.7 0 01-.66.48H5.86a.7.7 0 01-.66-.48l-.7-2.16a.7.7 0 01.25-.78L6.59 4.5a.7.7 0 01.82 0z"></path></g></svg>
                    </span>
                }
                {
                    player.outSubsitution > 0 &&
                    <span className="icon_f ic_thay_nguoi">
                        <svg className="ic ic-arrow arrowdownred" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                            <path d="M374.6 310.6l-160 160C208.4 476.9 200.2 480 192 480s-16.38-3.125-22.62-9.375l-160-160c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L160 370.8V64c0-17.69 14.33-31.1 31.1-31.1S224 46.31 224 64v306.8l105.4-105.4c12.5-12.5 32.75-12.5 45.25 0S387.1 298.1 374.6 310.6z" />
                        </svg>
                    </span>
                }
                {
                    (player.redCard > 0) &&
                    <span className="icon_f ic_the_do">
                        <span className="Common_iconWrapper__3p9Oo">
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="14" name="FootballRedCard"><rect width="10" height="14" x="2" rx="2" transform="translate(-1.66)" fill="#DF2357" fillRule="evenodd"></rect></svg>
                        </span>
                    </span>
                }
                {
                    (player.yellowTurnRedCard > 0) &&
                    <span className="icon_f ic_the_vang_do">
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="14" name="FootballRedCard"><rect width="10" height="14" x="2" rx="2" transform="translate(-1.66)" fill="#DF2357" fillRule="evenodd"></rect></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="14" name="FootballYellowCard"><rect width="10" height="14" x="2" rx="2" transform="translate(-1.68)" fill="#FFCE00" fillRule="evenodd"></rect></svg>
                    </span>
                }
                {
                    (player.yellowCard > 0 && player.yellowTurnRedCard === 0) &&
                    <span className="icon_f ic_the_vang">
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="14" name="FootballYellowCard"><rect width="10" height="14" x="2" rx="2" transform="translate(-1.68)" fill="#FFCE00" fillRule="evenodd"></rect></svg>
                    </span>
                }
            </>
        )
    }

    renderListPlayer = (title, home, away) => {
        return (
            <>
                <div className="line-up-change-men block-line-up">
                    <div className="block-line-up-main">
                        <div className="block-line-up-title">{title}</div>
                        <div className="block-line-up-body">
                            <div className="line-up">
                                <div className="line-up-table">
                                    <div className="table-paticipant home-team">

                                        {
                                            home.map((player, pIndex) => {
                                                return (
                                                    <ul key={pIndex} className="list-paticipant">
                                                        <li className="number-player">{player.playerNumber}</li>
                                                        <li className="name-player">{player.playerName}</li>
                                                        <li className="action-player">{this.renderIconListPlayer(player)}</li>
                                                    </ul>
                                                )
                                            })
                                        }
                                    </div>
                                    <div className="table-paticipant away-team">
                                        {
                                            away.map((player, pIndex) => {
                                                return (
                                                    <ul key={pIndex} className="list-paticipant">
                                                        <li className="number-player">{player.playerNumber}</li>
                                                        <li className="name-player">{player.playerName}</li>
                                                        <li className="action-player">{this.renderIconListPlayer(player)}</li>
                                                    </ul>
                                                )
                                            })
                                        }
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </>
        )
    }

    renderPitchView = () => {
        const lineup = this.props.match.lineup;
        const matchInfo = this.props.match.generalInfo;
        if (!lineup.generalInfo || !lineup.generalInfo.homeFormation || !lineup.generalInfo.awayFormation) {
            return null;
        }
        let homeLineup = this.getListLineup(lineup, IncidentPosition.Home);
        let awayLineup = this.getListLineup(lineup, IncidentPosition.Away);
        awayLineup = awayLineup.reverse();
        return (
            //map
            <div className="line-up-map">
                <h2 className="title-custom">Đội hình</h2>

                <div className="lineup-head">
                    <div className="team-name">
                        <img className="club-logo-img" src={matchInfo.homeTeam.icon} alt="" />
                        <div className="club-logo-text">{matchInfo.homeTeam.teamNameEN}</div>
                    </div>
                    <div className="f-right-text">{lineup.generalInfo.homeFormation}</div>
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
                                                            <img src={Site.resolveClientUrl(`css/images/dress-white.gif`)} alt="" />
                                                            <div className="team-number">
                                                                {player.number}
                                                                {this.renderIconLineup(player)}
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
                    <div className="team-content">
                        {
                            awayLineup.map((line, lineIndex) => {
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
                                                                {this.renderIconLineup(player)}
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
                        <img className="club-logo-img" src={matchInfo.awayTeam.icon} alt="" />
                        <div className="club-logo-text">{matchInfo.awayTeam.teamNameEN}</div>
                    </div>
                    <div className="f-right-text">{lineup.generalInfo.awayFormation}</div>
                </div>
            </div>
        )
    }


    getTeamSubstitution = position => {
        if (!this.props.match.incidents) {
            return null;
        }
        const incidents = CommonFunctions.produceIncidents(this.props.match.incidents);
        const lineup = this.props.match.lineup;
        const players = position === IncidentPosition.Home ? lineup.home : lineup.away;
        const substitution = incidents.filter(item => item.type === IncidentStatus.Substitution && item.position === position);
        if (!substitution) {
            return null;
        }
        return (
            substitution.map((incident, key) => {
                const inPlayer = players.find(item => item.playerId === incident.inPlayerId);
                const outPlayer = players.find(item => item.playerId === incident.outPlayerId)
                return (
                    <ul key={key} className="list-paticipant list-change-main">
                        <li className="number-player">{incident.timeShow}'</li>
                        <li className="name-player">
                            <p>{inPlayer?.playerNumber || 0} - {incident.inPlayerName}</p>
                            <p>{outPlayer?.playerNumber || 0} - {incident.outPlayerName}</p>
                        </li>
                    </ul>
                )
            })
        )
    }

    renderSubstitution = () => {
        const homeSub = this.getTeamSubstitution(IncidentPosition.Home);
        const awaySub = this.getTeamSubstitution(IncidentPosition.Away);
        if (homeSub.length === 0 && awaySub.length === 0) {
            return null;
        }
        return (
            <div className="line-up-change-men block-line-up">
                <div className="block-line-up-main">
                    <div className="block-line-up-title">Thay người</div>
                    <div className="block-line-up-body">
                        <div className="line-up">
                            <div className="line-up-table">
                                <div className="table-paticipant home-team">
                                    {homeSub}
                                </div>
                                <div className="table-paticipant away-team">
                                    {awaySub}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        )
    }



    render() {
        const { lineup } = this.props.match;
        if (!this.props.match.lineup || !this.props.match.lineup.generalInfo || (lineup.home.length === 0 && lineup.away.length === 0)) {
            return null;
        }
        const homePlayers = lineup.home.filter(player => player.position !== 0);
        const awayPlayers = lineup.away.filter(player => player.position !== 0);

        const homeSubPlayers = lineup.home.filter(player => player.position === 0).sort((a, b) => (a.playerNumber - b.playerNumber));
        const awaySubPlayers = lineup.away.filter(player => player.position === 0).sort((a, b) => (a.playerNumber - b.playerNumber));

        return (
            <div id="doi-hinh">
                {this.renderPitchView()}
                <div className="line-up-main">
                    <div className="line-up-team">
                        {this.renderListPlayer(LangHelper.getResource('LineupPlayer'), homePlayers, awayPlayers)}
                    </div>
                    <div className="line-up-team">
                        {this.renderListPlayer(LangHelper.getResource('LineupSubtitudePlayer'), homeSubPlayers, awaySubPlayers)}
                    </div>
                    {this.renderSubstitution()}
                    {
                        (lineup.generalInfo.homeCoach || lineup.generalInfo.awayCoach) &&
                        <div className="line-up-change-men block-line-up">
                            <div className="block-line-up-main">
                                <div className="block-line-up-title">Huấn luyện viên</div>
                                <div className="block-line-up-body">
                                    <div className="line-up">
                                        <div className="line-up-table">
                                            <div className="table-paticipant home-team">
                                                <ul className="list-paticipant">
                                                    <li className="name-player">{lineup.generalInfo.homeCoach}</li>
                                                </ul>
                                            </div>
                                            <div className="table-paticipant away-team">
                                                <ul className="list-paticipant">
                                                    <li className="name-player">{lineup.generalInfo.awayCoach}</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    }
                </div>
            </div>
        );
    }
}
const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { match } = state;
    return {
        match
    };
};

export default connect(mapStateToProps, mapDispatchToProps)(Lineups);