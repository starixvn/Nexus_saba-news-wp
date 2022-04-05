import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import { IncidentStatus, IncidentPosition } from '@jsroot/common/constants';
import IncidentIcon from '@jsroot/components/Icon/IncidentIcon'
import Site from '@jsroot/common/site';

export class Lineups extends Component {
    constructor(props) {
        super(props);
    }
    componentDidMount() {
        this.props.getLineupDetail();
    }

    getPlayerIncident = playerId => {
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
                    <span className="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" name="FootballGoal"><g transform="translate(.63)" fill="none" fillRule="evenodd"><circle cx="7" cy="7" r="6" fill="#FDFDFD"></circle><path fill="#222" d="M7 0h.28H7a6.8 6.8 0 01.56.02l.14.01.25.03h.02l.04.01.22.04.13.02.14.03.13.03.24.06.08.03.23.07.04.01.53.2.03.01c.95.42 1.8 1.03 2.47 1.8a5.96 5.96 0 01.62.82l.08.13.06.1c.42.7.72 1.49.87 2.32l.02.07a7.47 7.47 0 01.07 1.85 6.97 6.97 0 01-.94 2.9l-.03.05-.08.13.1-.18-.2.34-.03.04-.37.5-.04.03c-.12.16-.26.3-.4.45l-.05.05-.12.12-.4.35-.07.05-.19.15-.04.02v.01l-.25.17.25-.18a7.3 7.3 0 01-1.67.9l-.05.02-.22.08-.06.02a5.9 5.9 0 01-.37.1l-.16.04a6.95 6.95 0 01-3.11.01l-.06-.01-.15-.04-.09-.02-.03-.01a6.16 6.16 0 01-.24-.07l-.09-.03-.2-.07-.06-.02a7.96 7.96 0 01-.24-.1h-.03c-.5-.22-.96-.48-1.38-.79h-.01l-.04-.03-.2-.16.24.18a6.66 6.66 0 01-.82-.7l-.05-.04a6.47 6.47 0 01-.4-.45l-.04-.04A6.97 6.97 0 01.03 7.66a7.5 7.5 0 010-1.34l.02-.13.01-.11.04-.27.02-.11c.16-.82.45-1.59.87-2.28l.06-.1.08-.13A6.94 6.94 0 014.22.58l.04-.02.51-.2.06-.01.23-.08.06-.01.25-.07c.05 0 .09-.02.13-.03l.14-.03.13-.02L6 .07h.06L6.3.03h.14A3.85 3.85 0 017 0zm1.88 1.3L7.44 2.45a.7.7 0 01-.8.05l-.08-.05L5.12 1.3a6 6 0 00-2.96 2.16l.65 1.72a.7.7 0 01-.2.78L2.54 6 1 7.02v.2a5.96 5.96 0 001.14 3.29l1.83-.09a.7.7 0 01.68.43l.03.09.49 1.77a5.94 5.94 0 003.66 0l.49-1.77a.7.7 0 01.62-.51h.09l1.84.08A5.96 5.96 0 0013 7.02l-1.54-1.01a.7.7 0 01-.3-.75l.03-.08.65-1.72A6.01 6.01 0 008.88 1.3zM7.4 4.5l1.84 1.33c.24.18.35.5.25.79l-.7 2.16a.7.7 0 01-.66.48H5.86a.7.7 0 01-.66-.48l-.7-2.16a.7.7 0 01.25-.78L6.59 4.5a.7.7 0 01.82 0z"></path></g></svg>
                    </span>
                }
                {
                    playerIncident.ownGoals > 0 &&
                    <svg className="ic ic-ball fill-red" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.678.549A7.5,7.5,0,0,0,2.2,12.8a7.494,7.494,0,0,0,10.607,0A7.494,7.494,0,0,0,12.8,2.2,7.51,7.51,0,0,0,4.678.549Zm-2.8,10.109a5.148,5.148,0,0,1-.989-.8,7.071,7.071,0,0,1-.228-3.94,6.3,6.3,0,0,1,.48-.8c.056-.079.107-.16.167-.237L3,5.89v.042a13.038,13.038,0,0,0,.272,2.7s0,.006,0,.01L1.959,10.705C1.93,10.691,1.9,10.675,1.875,10.657Zm5.032,3.479a6.444,6.444,0,0,0,.9.377A7.034,7.034,0,0,1,4,13.592L6.84,14.1C6.862,14.112,6.884,14.125,6.907,14.137Zm-.01-.512-3.348-.6A10.455,10.455,0,0,1,2.5,11.511c-.077-.137-.163-.27-.228-.412L3.584,9.043l.1.025a21.761,21.761,0,0,0,2.5.5l.125.017L7.875,12.3q-.409.575-.831,1.142C7,13.5,6.948,13.565,6.9,13.625Zm5.57-1.158a6.977,6.977,0,0,1-.846.721,6.428,6.428,0,0,0-.253-1.153l1.677-3.129a7.835,7.835,0,0,0,1.321-.745.591.591,0,0,0,.134-.128A6.989,6.989,0,0,1,12.467,12.467Zm-.074-4.058c.062.119.124.239.186.361l-1.62,3.022-.028.005a14.776,14.776,0,0,1-2.649.255l-1.617-2.8q.377-.7.748-1.405c.168-.318.333-.639.5-.959l3.346-.344A18.677,18.677,0,0,1,12.393,8.409Zm-.679-3.584c-.159.419-.329.834-.5,1.25l-3.259.335c-.039-.051-.079-.1-.122-.15q-.577-.654-1.159-1.3l.011-.012-.469-.468.907-2.732a1.273,1.273,0,0,1,.189-.037c.493-.05.987-.107,1.482-.136a7.207,7.207,0,0,1,.915,0L11.957,4.1C11.879,4.341,11.8,4.586,11.714,4.825Zm.753-2.292a6.993,6.993,0,0,1,1.438,2.082,7.13,7.13,0,0,0-1.34-.72c-.073-.03-.149-.057-.225-.083l-2.21-2.491A2.922,2.922,0,0,0,10.157,1,6.991,6.991,0,0,1,12.467,2.533ZM5.251.867c.009.006.013.012.018.013a5.617,5.617,0,0,1,1.4.736L5.819,4.169l-.013,0c-.173.072-.35.136-.521.214a14.945,14.945,0,0,0-2.036,1.1L1.576,4.5a.506.506,0,0,1,.029-.153c.112-.3.223-.6.35-.894a6.7,6.7,0,0,1,.331-.657c.08-.088.162-.175.247-.26A6.983,6.983,0,0,1,5.266.841Z" transform="translate(0 0)"></path>
                    </svg>
                }
                {
                    playerIncident.penalty > 0 &&
                    <span className="">
                        <span>P</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" name="FootballGoal"><g transform="translate(.63)" fill="none" fillRule="evenodd"><circle cx="7" cy="7" r="6" fill="#FDFDFD"></circle><path fill="#222" d="M7 0h.28H7a6.8 6.8 0 01.56.02l.14.01.25.03h.02l.04.01.22.04.13.02.14.03.13.03.24.06.08.03.23.07.04.01.53.2.03.01c.95.42 1.8 1.03 2.47 1.8a5.96 5.96 0 01.62.82l.08.13.06.1c.42.7.72 1.49.87 2.32l.02.07a7.47 7.47 0 01.07 1.85 6.97 6.97 0 01-.94 2.9l-.03.05-.08.13.1-.18-.2.34-.03.04-.37.5-.04.03c-.12.16-.26.3-.4.45l-.05.05-.12.12-.4.35-.07.05-.19.15-.04.02v.01l-.25.17.25-.18a7.3 7.3 0 01-1.67.9l-.05.02-.22.08-.06.02a5.9 5.9 0 01-.37.1l-.16.04a6.95 6.95 0 01-3.11.01l-.06-.01-.15-.04-.09-.02-.03-.01a6.16 6.16 0 01-.24-.07l-.09-.03-.2-.07-.06-.02a7.96 7.96 0 01-.24-.1h-.03c-.5-.22-.96-.48-1.38-.79h-.01l-.04-.03-.2-.16.24.18a6.66 6.66 0 01-.82-.7l-.05-.04a6.47 6.47 0 01-.4-.45l-.04-.04A6.97 6.97 0 01.03 7.66a7.5 7.5 0 010-1.34l.02-.13.01-.11.04-.27.02-.11c.16-.82.45-1.59.87-2.28l.06-.1.08-.13A6.94 6.94 0 014.22.58l.04-.02.51-.2.06-.01.23-.08.06-.01.25-.07c.05 0 .09-.02.13-.03l.14-.03.13-.02L6 .07h.06L6.3.03h.14A3.85 3.85 0 017 0zm1.88 1.3L7.44 2.45a.7.7 0 01-.8.05l-.08-.05L5.12 1.3a6 6 0 00-2.96 2.16l.65 1.72a.7.7 0 01-.2.78L2.54 6 1 7.02v.2a5.96 5.96 0 001.14 3.29l1.83-.09a.7.7 0 01.68.43l.03.09.49 1.77a5.94 5.94 0 003.66 0l.49-1.77a.7.7 0 01.62-.51h.09l1.84.08A5.96 5.96 0 0013 7.02l-1.54-1.01a.7.7 0 01-.3-.75l.03-.08.65-1.72A6.01 6.01 0 008.88 1.3zM7.4 4.5l1.84 1.33c.24.18.35.5.25.79l-.7 2.16a.7.7 0 01-.66.48H5.86a.7.7 0 01-.66-.48l-.7-2.16a.7.7 0 01.25-.78L6.59 4.5a.7.7 0 01.82 0z"></path></g></svg>
                    </span>
                }
                {
                    playerIncident.outSubsitution > 0 &&
                    <svg className="ic ic-arrow arrowdownred" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                        <path d="M374.6 310.6l-160 160C208.4 476.9 200.2 480 192 480s-16.38-3.125-22.62-9.375l-160-160c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L160 370.8V64c0-17.69 14.33-31.1 31.1-31.1S224 46.31 224 64v306.8l105.4-105.4c12.5-12.5 32.75-12.5 45.25 0S387.1 298.1 374.6 310.6z" />
                    </svg>
                }
                {
                    playerIncident.inSubsitution > 0 &&
                    <svg className="ic ic-arrow arrowupgreen" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                        <path d="M34.9 289.5l-22.2-22.2c-9.4-9.4-9.4-24.6 0-33.9L207 39c9.4-9.4 24.6-9.4 33.9 0l194.3 194.3c9.4 9.4 9.4 24.6 0 33.9L413 289.4c-9.5 9.5-25 9.3-34.3-.4L264 168.6V456c0 13.3-10.7 24-24 24h-32c-13.3 0-24-10.7-24-24V168.6L69.2 289.1c-9.3 9.8-24.8 10-34.3.4z" />
                    </svg>
                }
                {
                    (playerIncident.redCard > 0) &&
                    <span className="">
                        <span className="Common_iconWrapper__3p9Oo">
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="14" name="FootballRedCard"><rect width="10" height="14" x="2" rx="2" transform="translate(-1.66)" fill="#DF2357" fillRule="evenodd"></rect></svg>
                        </span>
                    </span>
                }
                {
                    (playerIncident.yellowTurnRedCard > 0) &&
                    <span className="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="14" name="FootballRedCard"><rect width="10" height="14" x="2" rx="2" transform="translate(-1.66)" fill="#DF2357" fillRule="evenodd"></rect></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="14" name="FootballYellowCard"><rect width="10" height="14" x="2" rx="2" transform="translate(-1.68)" fill="#FFCE00" fillRule="evenodd"></rect></svg>
                    </span>
                }
                {
                    (playerIncident.yellowCard > 0 && playerIncident.yellowTurnRedCard === 0) &&
                    <span className="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="14" name="FootballYellowCard"><rect width="10" height="14" x="2" rx="2" transform="translate(-1.68)" fill="#FFCE00" fillRule="evenodd"></rect></svg>
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
                            home.map((player, pIndex) => {
                                return (
                                    <tr key={pIndex}>
                                        <td>{player.playerNumber}</td>
                                        <td>{player.playerName}</td>
                                        <td>{this.renderIconListPlayer(player)}</td>
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
                            away.map((player, pIndex) => {
                                return (
                                    <tr key={pIndex}>
                                        <td>{player.playerNumber}</td>
                                        <td>{player.playerName}</td>
                                        <td>{this.renderIconListPlayer(player)}</td>
                                    </tr>
                                )
                            })
                        }
                    </tbody>
                </table>
            </div>
        )
    }

    renderLineup = () => {
        const lineup = this.props.lineup;
        const matchInfo = this.props.soccerMatch.generalInfo;
        if (!lineup.generalInfo || !lineup.generalInfo.homeFormation || !lineup.generalInfo.awayFormation) {
            return null;
        }
        let homeLineup = this.getListLineup(lineup, IncidentPosition.Home);
        let awayLineup = this.getListLineup(lineup, IncidentPosition.Away);
        let awayLineupReverse = awayLineup.reverse();
        return (
            <div className="lineup">
                <div className="lineup-head">
                    <div className="team-name">
                        <img className="club-logo-img" src={matchInfo.homeTeamIcon} alt="" />
                        <div className="club-logo-text">{matchInfo.homeTeamNameEN}</div>
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
                        <img className="club-logo-img" src={matchInfo.awayTeamIcon} alt="" />
                        <div className="club-logo-text">{matchInfo.awayTeamNameEN}</div>
                    </div>
                    <div className="f-right-text">{lineup.generalInfo.awayFormation}</div>
                </div>
            </div>
        )
    }


    render() {
        console.log("lineup");
        if (!this.props.lineup || !this.props.lineup.generalInfo) {
            return null;
        }
        const lineup = this.props.lineup;
        const incidents = this.props.soccerMatch.incidents;

        const homePlayers = lineup.home.filter(player => player.position !== 0);
        const awayPlayers = lineup.away.filter(player => player.position !== 0);

        const homeSubPlayers = lineup.home.filter(player => player.position === 0);
        const awaySubPlayers = lineup.away.filter(player => player.position === 0);

        const homeSubstitution = incidents.filter(item => item.type === IncidentStatus.Substitution && item.position === IncidentPosition.Home);
        const awaySubstitution = incidents.filter(item => item.type === IncidentStatus.Substitution && item.position === IncidentPosition.Away);
        return (
            <>
                {this.renderLineup()}
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