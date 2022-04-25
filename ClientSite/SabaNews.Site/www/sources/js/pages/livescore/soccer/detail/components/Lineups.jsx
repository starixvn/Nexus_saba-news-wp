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
                    <>
                    {
                        playerIncident.goals > 1 &&
                        <span>{playerIncident.goals}</span>
                    }
                        <span className="icon-line-up">
                            <i className="icon-livescore icon-ball" />
                        </span>
                    </>
                }
                {
                    playerIncident.ownGoals > 0 &&
                    <>
                    {
                        playerIncident.ownGoals > 1 &&
                        <span>{playerIncident.ownGoals}</span>
                    }
                        <span className="icon-line-up">
                            <svg width="31" height="24" viewBox="0 0 31 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="19" cy="12" r="12" fill="white" />
                                <path d="M26.6395 4.37039C25.6394 3.36372 24.4493 2.56518 23.138 2.02101C21.8268 1.47685 20.4205 1.19786 19.0006 1.20021C17.5803 1.19799 16.1737 1.47703 14.8622 2.02118C13.5506 2.56533 12.3601 3.3638 11.3595 4.37039C7.14677 8.57644 7.14677 15.4229 11.3595 19.63C12.3601 20.6369 13.5508 21.4355 14.8626 21.9797C16.1743 22.5238 17.5812 22.8027 19.0016 22.8002C20.4215 22.8027 21.8278 22.5239 23.139 21.9799C24.4502 21.4359 25.6404 20.6376 26.6405 19.6311C30.8533 15.425 30.8533 8.5786 26.6395 4.37039ZM25.7448 17.3953H23.3213L21.9621 20.1102C21.0139 20.46 20.0114 20.6401 19.0006 20.6421C17.9879 20.6403 16.9834 20.4598 16.0336 20.1091L14.6776 17.4061H12.2628C11.3719 16.3 10.7694 14.9906 10.5092 13.595L12.5167 10.9212L11.2028 8.29589C11.6221 7.40525 12.1919 6.59345 12.8873 5.89614C13.8606 4.92076 15.0535 4.19226 16.3664 3.77153L18.9995 5.52603L21.6337 3.77261C22.9462 4.19363 24.139 4.92168 25.1128 5.89614C25.8074 6.59262 26.3768 7.40329 26.7961 8.29266L25.4823 10.9212L27.4898 13.595C27.2308 14.9861 26.6313 16.2916 25.7448 17.3953Z" fill="#EE2316" />
                                <path d="M15.3999 11.1691L16.9428 15.5999H21.057L22.5999 11.1691L18.9999 8.3999L15.3999 11.1691Z" fill="#EE2316" />
                                <rect width="19" height="13" rx="5" fill="white" />
                                <path d="M9.58293 7.06299C9.58293 8.36279 9.26066 9.36182 8.61613 10.0601C7.9716 10.7583 7.04777 11.1074 5.84465 11.1074C4.64152 11.1074 3.7177 10.7583 3.07316 10.0601C2.42863 9.36182 2.10637 8.35921 2.10637 7.05225C2.10637 5.74528 2.42863 4.74805 3.07316 4.06055C3.72128 3.36947 4.64868 3.02393 5.85539 3.02393C7.0621 3.02393 7.98413 3.37126 8.6215 4.06592C9.26245 4.76058 9.58293 5.7596 9.58293 7.06299ZM3.85197 7.06299C3.85197 7.94027 4.01848 8.60091 4.35148 9.04492C4.68449 9.48893 5.18221 9.71094 5.84465 9.71094C7.1731 9.71094 7.83732 8.82829 7.83732 7.06299C7.83732 5.29411 7.17668 4.40967 5.85539 4.40967C5.19296 4.40967 4.69344 4.63346 4.35686 5.08105C4.02027 5.52507 3.85197 6.18571 3.85197 7.06299ZM13.5313 6.59033H16.6466V10.6616C16.1417 10.8263 15.6654 10.9409 15.2179 11.0054C14.7738 11.0734 14.3191 11.1074 13.8536 11.1074C12.6684 11.1074 11.7624 10.7601 11.1358 10.0654C10.5128 9.36719 10.2013 8.36637 10.2013 7.06299C10.2013 5.79541 10.5629 4.80713 11.2862 4.09814C12.0131 3.38916 13.0193 3.03467 14.3048 3.03467C15.1104 3.03467 15.8874 3.1958 16.6358 3.51807L16.0826 4.8501C15.5097 4.56364 14.9135 4.42041 14.294 4.42041C13.5743 4.42041 12.9978 4.66211 12.5645 5.14551C12.1313 5.62891 11.9146 6.27881 11.9146 7.09521C11.9146 7.94743 12.0883 8.59912 12.4356 9.05029C12.7865 9.49788 13.295 9.72168 13.961 9.72168C14.3083 9.72168 14.661 9.68587 15.0191 9.61426V7.97607H13.5313V6.59033Z" fill="#EE2316" />
                            </svg>
                        </span>
                    </>
                }
                {
                    playerIncident.penalty > 0 &&
                    <>
                        {
                            playerIncident.penalty > 1 &&
                            <span>{playerIncident.penalty}</span>
                        }
                        <span className="icon-line-up">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="12" r="12" fill="white" />
                                <path d="M19.6395 4.37039C18.6394 3.36372 17.4493 2.56518 16.138 2.02101C14.8268 1.47685 13.4205 1.19786 12.0006 1.20021C10.5803 1.19799 9.17373 1.47703 7.86216 2.02118C6.5506 2.56533 5.36009 3.3638 4.3595 4.37039C0.146767 8.57644 0.146767 15.4229 4.3595 19.63C5.36011 20.6369 6.55077 21.4355 7.86256 21.9797C9.17434 22.5238 10.5812 22.8027 12.0016 22.8002C13.4215 22.8027 14.8278 22.5239 16.139 21.9799C17.4502 21.4359 18.6404 20.6376 19.6405 19.6311C23.8533 15.425 23.8533 8.5786 19.6395 4.37039ZM18.7448 17.3953H16.3213L14.9621 20.1102C14.0139 20.46 13.0114 20.6401 12.0006 20.6421C10.9879 20.6403 9.98345 20.4598 9.0336 20.1091L7.67762 17.4061H5.26277C4.37186 16.3 3.76937 14.9906 3.50918 13.595L5.51668 10.9212L4.20284 8.29589C4.62206 7.40525 5.19189 6.59345 5.88728 5.89614C6.86055 4.92076 8.0535 4.19226 9.36638 3.77153L11.9995 5.52603L14.6337 3.77261C15.9462 4.19363 17.139 4.92168 18.1128 5.89614C18.8074 6.59262 19.3768 7.40329 19.7961 8.29266L18.4823 10.9212L20.4898 13.595C20.2308 14.9861 19.6313 16.2916 18.7448 17.3953Z" fill="#324A75" />
                                <path d="M8.3999 11.1691L9.94276 15.5999H14.057L15.5999 11.1691L11.9999 8.3999L8.3999 11.1691Z" fill="#324A75" />
                                <circle cx="6.5" cy="6.5" r="6.5" fill="white" />
                                <path d="M6.19971 6.84277H6.74756C7.2596 6.84277 7.64274 6.74251 7.89697 6.54199C8.1512 6.33789 8.27832 6.04248 8.27832 5.65576C8.27832 5.26546 8.1709 4.97721 7.95605 4.79102C7.74479 4.60482 7.41178 4.51172 6.95703 4.51172H6.19971V6.84277ZM9.95947 5.59668C9.95947 6.44173 9.6945 7.08805 9.16455 7.53564C8.63818 7.98324 7.88802 8.20703 6.91406 8.20703H6.19971V11H4.53467V3.14746H7.04297C7.99544 3.14746 8.71875 3.35335 9.21289 3.76514C9.71061 4.17334 9.95947 4.78385 9.95947 5.59668Z" fill="#324A75" />
                            </svg>
                        </span>
                    </>
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
                    <>
                        <span className="icon_f ic_bong">
                            {
                                player.goals > 1 &&
                                <span className="number-goal">{player.goals}</span>
                            }
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="12" r="10" fill="white" />
                                <path d="M18.3663 5.64183C17.5329 4.80294 16.5411 4.13749 15.4484 3.68402C14.3557 3.23054 13.1838 2.99805 12.0005 3.00001C10.817 2.99816 9.64481 3.23069 8.55184 3.68415C7.45887 4.13762 6.46678 4.803 5.63296 5.64183C2.12235 9.14687 2.12235 14.8522 5.63296 18.3582C6.4668 19.1972 7.45902 19.8628 8.55217 20.3162C9.64533 20.7697 10.8177 21.0021 12.0014 21C13.1846 21.0021 14.3565 20.7697 15.4492 20.3164C16.5419 19.8631 17.5337 19.1978 18.3672 18.3591C21.8778 14.854 21.8778 9.14867 18.3663 5.64183ZM17.6207 16.4959H15.6012L14.4685 18.7583C13.6783 19.0498 12.8429 19.1999 12.0005 19.2016C11.1566 19.2001 10.3196 19.0497 9.52804 18.7574L8.39805 16.5049H6.38568C5.64326 15.5832 5.14118 14.492 4.92436 13.329L6.59728 11.1008L5.5024 8.91308C5.85176 8.17088 6.32662 7.49438 6.90611 6.91328C7.71717 6.10047 8.71129 5.49338 9.80536 5.14278L11.9996 6.60486L14.1948 5.14368C15.2885 5.49453 16.2825 6.10124 17.094 6.91328C17.6729 7.49368 18.1474 8.16924 18.4968 8.91038L17.4019 11.1008L19.0749 13.329C18.859 14.4882 18.3595 15.5762 17.6207 16.4959Z" fill="#324A75" />
                                <path d="M9 11.3077L10.2857 15H13.7143L15 11.3077L12 9L9 11.3077Z" fill="#324A75" />
                            </svg>
                        </span>
                    </>
                }
                {
                    player.ownGoals > 0 &&
                    <>
                        <span className="icon_f ic_ownbong">
                            {
                                player.goals > 1 &&
                                <span className="number-goal">{player.goals}</span>
                            }
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="14" cy="12" r="10" fill="white" />
                                <path d="M20.3663 5.64183C19.5329 4.80294 18.5411 4.13749 17.4484 3.68402C16.3557 3.23054 15.1838 2.99805 14.0005 3.00001C12.817 2.99816 11.6448 3.23069 10.5518 3.68416C9.45888 4.13762 8.46678 4.80301 7.63296 5.64183C4.12235 9.14688 4.12235 14.8522 7.63296 18.3582C8.4668 19.1972 9.45902 19.8628 10.5522 20.3162C11.6453 20.7697 12.8177 21.0021 14.0014 21C15.1846 21.0021 16.3565 20.7697 17.4492 20.3164C18.5419 19.8631 19.5337 19.1978 20.3672 18.3591C23.8778 14.854 23.8778 9.14868 20.3663 5.64183ZM19.6207 16.496H17.6012L16.4685 18.7583C15.6783 19.0499 14.8429 19.1999 14.0005 19.2016C13.1566 19.2001 12.3196 19.0497 11.528 18.7574L10.3981 16.505H8.38569C7.64326 15.5832 7.14118 14.492 6.92436 13.329L8.59728 11.1008L7.50241 8.91309C7.85176 8.17089 8.32662 7.49438 8.90611 6.91329C9.71717 6.10047 10.7113 5.49339 11.8054 5.14278L13.9996 6.60487L16.1948 5.14368C17.2885 5.49453 18.2825 6.10124 19.094 6.91329C19.6729 7.49369 20.1474 8.16925 20.4968 8.91039L19.402 11.1008L21.0749 13.329C20.859 14.4882 20.3595 15.5762 19.6207 16.496Z" fill="#EE2316" />
                                <path d="M11 11.3077L12.2857 15H15.7143L17 11.3077L14 9L11 11.3077Z" fill="#EE2316" />
                                <rect y="2" width="15" height="9" rx="3.87097" fill="white" />
                                <path d="M7.14081 6.32662C7.14081 7.2914 6.90161 8.03293 6.4232 8.5512C5.9448 9.06948 5.25908 9.32861 4.36606 9.32861C3.47304 9.32861 2.78732 9.06948 2.30892 8.5512C1.83051 8.03293 1.59131 7.28875 1.59131 6.31865C1.59131 5.34855 1.83051 4.60835 2.30892 4.09805C2.78998 3.58509 3.47835 3.32861 4.37403 3.32861C5.26971 3.32861 5.9541 3.58642 6.42719 4.10204C6.90294 4.61765 7.14081 5.35918 7.14081 6.32662ZM2.88699 6.32662C2.88699 6.97778 3.01058 7.46815 3.25775 7.79772C3.50493 8.12728 3.87437 8.29207 4.36606 8.29207C5.35211 8.29207 5.84513 7.63692 5.84513 6.32662C5.84513 5.01366 5.35476 4.35718 4.37403 4.35718C3.88234 4.35718 3.51157 4.5233 3.26174 4.85552C3.01191 5.18509 2.88699 5.67546 2.88699 6.32662Z" fill="#EE2316" />
                                <path d="M10.0715 5.97579H12.3838V8.99772C12.0091 9.11998 11.6556 9.20503 11.3233 9.25287C10.9938 9.30336 10.6562 9.32861 10.3107 9.32861C9.43099 9.32861 8.75856 9.07081 8.29345 8.55519C7.83099 8.03692 7.59976 7.29406 7.59976 6.32662C7.59976 5.38576 7.8682 4.6522 8.40508 4.12596C8.94461 3.59971 9.69146 3.33659 10.6456 3.33659C11.2436 3.33659 11.8204 3.45619 12.3758 3.69539L11.9652 4.6841C11.54 4.47147 11.0974 4.36516 10.6376 4.36516C10.1034 4.36516 9.67551 4.54456 9.35391 4.90336C9.03232 5.26217 8.87152 5.74456 8.87152 6.35054C8.87152 6.9831 9.00043 7.46682 9.25823 7.8017C9.5187 8.13393 9.89611 8.30004 10.3905 8.30004C10.6483 8.30004 10.9101 8.27346 11.1758 8.22031V7.00436H10.0715V5.97579Z" fill="#EE2316" />
                            </svg>
                        </span>
                    </>
                }
                {
                    player.penalty > 0 &&
                    <>
                        <span className="icon_f ic_penalty">
                            {
                                player.goals > 1 &&
                                <span className="number-goal">{player.goals}</span>
                            }
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="12" r="10" fill="white" />
                                <path d="M18.3663 5.64183C17.5329 4.80294 16.5411 4.13749 15.4484 3.68402C14.3557 3.23054 13.1838 2.99805 12.0005 3.00001C10.817 2.99816 9.64481 3.23069 8.55184 3.68415C7.45887 4.13762 6.46678 4.803 5.63296 5.64183C2.12235 9.14687 2.12235 14.8522 5.63296 18.3582C6.4668 19.1972 7.45902 19.8628 8.55217 20.3162C9.64533 20.7697 10.8177 21.0021 12.0014 21C13.1846 21.0021 14.3565 20.7697 15.4492 20.3164C16.5419 19.8631 17.5337 19.1978 18.3672 18.3591C21.8778 14.854 21.8778 9.14867 18.3663 5.64183ZM17.6207 16.4959H15.6012L14.4685 18.7583C13.6783 19.0498 12.8429 19.1999 12.0005 19.2016C11.1566 19.2001 10.3196 19.0497 9.52804 18.7574L8.39805 16.5049H6.38568C5.64326 15.5832 5.14118 14.492 4.92436 13.329L6.59728 11.1008L5.5024 8.91308C5.85176 8.17088 6.32662 7.49438 6.90611 6.91328C7.71717 6.10047 8.71129 5.49338 9.80536 5.14278L11.9996 6.60486L14.1948 5.14368C15.2885 5.49453 16.2825 6.10124 17.094 6.91328C17.6729 7.49368 18.1474 8.16924 18.4968 8.91038L17.4019 11.1008L19.0749 13.329C18.859 14.4882 18.3595 15.5762 17.6207 16.4959Z" fill="#324A75" />
                                <path d="M9 11.3077L10.2857 15H13.7143L15 11.3077L12 9L9 11.3077Z" fill="#324A75" />
                                <circle cx="7" cy="7" r="5" fill="white" />
                                <path d="M6.76908 7.10964H7.1905C7.58439 7.10964 7.87911 7.03252 8.07467 6.87827C8.27023 6.72127 8.36801 6.49403 8.36801 6.19655C8.36801 5.89632 8.28538 5.67459 8.12012 5.53136C7.95761 5.38813 7.70145 5.31652 7.35164 5.31652H6.76908V7.10964ZM9.66121 6.1511C9.66121 6.80115 9.45738 7.29832 9.04973 7.64262C8.64483 7.98692 8.06778 8.15907 7.31858 8.15907H6.76908V10.3075H5.48828V4.26709H7.41774C8.15042 4.26709 8.70681 4.42547 9.08691 4.74223C9.46978 5.05623 9.66121 5.52585 9.66121 6.1511Z" fill="#324A75" />
                            </svg>
                        </span>
                    </>
                }
                {
                    player.inSubsitution > 0 &&
                    <span className="icon_f ic_thay_nguoi">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="10" fill="white" />
                            <path fillRule="evenodd" clipRule="evenodd" d="M12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21ZM14.3143 11.9308V16.5H9.68574V11.9308H6.60003L12 6.60001L17.4 11.9308H14.3143Z" fill="#42AD40" />
                        </svg>
                    </span>
                }
                {
                    player.outSubsitution > 0 &&
                    <span className="icon_f ic_thay_nguoi">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="10" fill="white" />
                            <path fillRule="evenodd" clipRule="evenodd" d="M12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3ZM9.68571 12.0692L9.68571 7.5L14.3143 7.5L14.3143 12.0692L17.4 12.0692L12 17.4L6.6 12.0692L9.68571 12.0692Z" fill="#FF5744" />
                        </svg>
                    </span>
                }
                {
                    (player.redCard > 0) &&
                    <span className="icon_f ic_the_do">
                        <span className="Common_iconWrapper__3p9Oo">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="12" r="10" fill="white" />
                                <rect x="6.18188" y="5.07959" width="11.8182" height="13.2955" rx="2.21591" fill="#FF5744" />
                            </svg>
                        </span>
                    </span>
                }
                {
                    (player.yellowTurnRedCard > 0) &&
                    <span className="icon_f ic_the_vang_do">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="10" fill="white" />
                            <rect x="8.54541" y="7.54541" width="10.1818" height="11.4545" rx="1.90909" fill="#F9C91E" />
                            <rect x="6" y="5" width="10.1818" height="12.0909" rx="1.90909" fill="#FF5744" />
                        </svg>
                    </span>
                }
                {
                    (player.yellowCard > 0 && player.yellowTurnRedCard === 0) &&
                    <span className="icon_f ic_the_vang">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="10" fill="white" />
                            <rect x="6.18188" y="5.07959" width="11.8182" height="13.2955" rx="2.21591" fill="#F9C91E" />
                        </svg>
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

                <div className="line-up-map-main">
                    <div className="line-up-map-head">
                        <div className="team">
                            <div className="team-head-name">{matchInfo.homeTeam.teamNameEN}</div>
                            <div className="team-head-number">{lineup.generalInfo.homeFormation}</div>
                        </div>
                    </div>
                    <div className="line-up-map-body">
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
                                                                <img src={Site.resolveClientUrl(`css/images/red.png`)} alt="" />
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
                                                                <img src={Site.resolveClientUrl(`css/images/blue.png`)} alt="" />
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
                    <div className="line-up-map-head">
                        <div className="team">
                            <div className="team-head-name">{matchInfo.awayTeam.teamNameEN}</div>
                            <div className="team-head-number">{lineup.generalInfo.awayFormation}</div>
                        </div>
                    </div>
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