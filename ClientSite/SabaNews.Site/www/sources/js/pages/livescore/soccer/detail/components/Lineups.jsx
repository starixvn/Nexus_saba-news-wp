import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import { IncidentStatus, IncidentPosition, IncidentIconType } from '@jsroot/common/constants';
import Site from '@jsroot/common/site';
import LangHelper from '@jsroot/common/langHelper';
import CommonFunctions from '@jsroot/common/common-functions';
import IncidentIcon from '@jsroot/components/Icon/IncidentIcon';

export class Lineups extends Component {
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
        };

        incidents && incidents.map(item => {
            if (item.playerId === playerId) {
                playerIncident = this.getGeneralIncident(item, playerIncident);
            }
            if (item.type === IncidentStatus.Substitution) {
                if (item.outPlayerId === playerId) {
                    playerIncident.outSubsitution++;
                }
                if (item.inPlayerId === playerId) {
                    playerIncident.inSubsitution++;
                }
            }
        });
        return playerIncident;
    };

    getGeneralIncident = (incident, playerIncident) => {
        switch (incident.type) {
            case IncidentStatus.Goal:
                playerIncident.goals++;
                break;
            case IncidentStatus.OwnGoal:
                playerIncident.ownGoals++;
                break;
            case IncidentStatus.Penalty:
                if (incident.homeScore !== 0 || incident.awayScore !== 0) {
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
                playerIncident.yellowTurnRedCard++;
                break;
        }
        return playerIncident;
    };

    getGoalsNumber = (goals, className) => {
        return (
            <>
                {
                    goals > 1 &&
                    <span className={className}>{goals}</span>
                }
            </>
        );
    };

    renderIconListPlayer = player => {
        const playerIncident = this.getPlayerIncident(player.playerId);
        const goalsClass = 'number-position';
        return (
            <>
                {
                    playerIncident.goals > 0 &&
                    <>
                        <span className="icon-line-up">
                            {this.getGoalsNumber(playerIncident.goals, goalsClass)}
                            <IncidentIcon type={IncidentIconType.Goal} />
                        </span>
                    </>
                }
                {
                    playerIncident.ownGoals > 0 &&
                    <>
                        <span className="icon-line-up">
                            {this.getGoalsNumber(playerIncident.ownGoals, goalsClass)}
                            <IncidentIcon type={IncidentIconType.OwnGoal} />
                        </span>
                    </>
                }
                {
                    playerIncident.penalty > 0 &&
                    <>
                        <span className="icon-line-up">
                            {this.getGoalsNumber(playerIncident.penalty, goalsClass)}
                            <IncidentIcon type={IncidentIconType.Penalty} />
                        </span>
                    </>
                }
                {
                    playerIncident.outSubsitution > 0 &&
                    <span className="icon-line-up">
                        <IncidentIcon type={IncidentIconType.outSubsitution} />
                    </span>
                }
                {
                    playerIncident.inSubsitution > 0 &&
                    <span className="icon-line-up">
                        <IncidentIcon type={IncidentIconType.inSubsitution} />
                    </span>
                }
                {
                    (playerIncident.redCard > 0) &&
                    <span className="icon-line-up">
                        <IncidentIcon type={IncidentIconType.RedCard} />
                    </span>
                }
                {
                    (playerIncident.yellowTurnRedCard > 0) &&
                    <span className="icon-line-up">
                        <IncidentIcon type={IncidentIconType.YellowTurnRedCard} />
                    </span>
                }
                {
                    (playerIncident.yellowCard > 0 && playerIncident.yellowTurnRedCard === 0) &&
                    <span className="icon-line-up">
                        <IncidentIcon type={IncidentIconType.YellowCard} />
                    </span>
                }
            </>
        );
    };

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
        const formationArray = formation.split('-');
        const goalKeeperIncident = this.getPlayerIncident(players[0].playerId);
        const playersInfo = [[
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
        formationArray.map(line => {
            const lineArray = [];
            for (let i = 0; i < line; i++) {
                if (!players[index]) {
                    break;
                }
                const incident = this.getPlayerIncident(players[index].playerId);
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

    };

    renderIconLineup = player => {
        const goalClass = 'number-goal';
        return (
            <>

                {this.getIconGoalLineup(player)}
                {
                    player.ownGoals > 0 &&
                    <span className="icon_f ic_ownbong">
                        {this.getGoalsNumber(player.ownGoals, goalClass)}
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="14" cy="12" r="10" fill="white" />
                            <path d="M20.3663 5.64183C19.5329 4.80294 18.5411 4.13749 17.4484 3.68402C16.3558 3.23054 15.1838 2.99805 14.0005 3.00001C12.817 2.99816 11.6449
                                3.23069 10.5519 3.68416C9.45891 4.13762 8.46681 4.80301 7.63299 5.64183C4.12238 9.14688 4.12238 14.8522 7.63299 18.3582C8.46683 19.1972 9.45905
                                19.8628 10.5522 20.3162C11.6454 20.7697 12.8177 21.0021 14.0014 21C15.1847 21.0021 16.3566 20.7697 17.4492 20.3164C18.5419 19.8631 19.5337
                                19.1978 20.3672 18.3591C23.8778 14.854 23.8778 9.14868 20.3663 5.64183ZM19.6208 16.496H17.6012L16.4685 18.7583C15.6784 19.0499 14.8429 19.1999
                                14.0005 19.2016C13.1566 19.2001 12.3196 19.0497 11.5281 18.7574L10.3981 16.505H8.38572C7.64329 15.5832 7.14121 14.492 6.92439 13.329L8.59731
                                11.1008L7.50244 8.91309C7.85179 8.17089 8.32665 7.49438 8.90614 6.91329C9.7172 6.10047 10.7113 5.49339 11.8054 5.14278L13.9996 6.60487L16.1948
                                5.14368C17.2885 5.49453 18.2825 6.10124 19.094 6.91329C19.6729 7.49369 20.1474 8.16925 20.4969 8.91039L19.402 11.1008L21.0749 13.329C20.8591
                                14.4882 20.3595 15.5762 19.6208 16.496Z" fill="#EE2316"
                            />
                            <path d="M11.0001 11.3077L12.2858 15H15.7144L17.0001 11.3077L14.0001 8.99998L11.0001 11.3077Z" fill="#EE2316" />
                            <rect y="2" width="15" height="9" rx="3.87097" fill="white" />
                            <path d="M7.14075 6.32671C7.14075 7.2915 6.90155 8.03302 6.42314 8.5513C5.94474 9.06957 5.25902 9.3287 4.366 9.3287C3.47298 9.3287 2.78726 9.06957
                                2.30886 8.5513C1.83045 8.03302 1.59125 7.28884 1.59125 6.31874C1.59125 5.34864 1.83045 4.60844 2.30886 4.09814C2.78992 3.58518 3.47829 3.3287
                                4.37397 3.3287C5.26965 3.3287 5.95404 3.58651 6.42713 4.10213C6.90288 4.61774 7.14075 5.35927 7.14075 6.32671ZM2.88693 6.32671C2.88693 6.97787
                                3.01052 7.46824 3.25769 7.79781C3.50487 8.12738 3.8743 8.29216 4.366 8.29216C5.35205 8.29216 5.84507 7.63701 5.84507 6.32671C5.84507 5.01375
                                5.3547 4.35728 4.37397 4.35728C3.88228 4.35728 3.51151 4.52339 3.26168 4.85562C3.01185 5.18518 2.88693 5.67555 2.88693 6.32671Z" fill="#EE2316"
                            />
                            <path d="M10.0715 5.97588H12.3838V8.99781C12.009 9.12007 11.6555 9.20512 11.3233 9.25296C10.9937 9.30346 10.6562 9.3287 10.3107 9.3287C9.43093 9.3287
                                3.5998 9.69139 3.33668 10.6455 3.33668C11.2436 3.33668 11.8203 3.45628 12.3758 3.69548L11.9651 4.68419C11.5399 4.47156 11.0974 4.36525 10.6376
                                3.33668 10.6455 3.33668C11.2436 3.33668 11.8203 3.45628 12.3758 3.69548L11.9651 4.68419C11.5399 4.47156 11.0974 4.36525 10.6376 4.36525C10.1034
                                4.36525 9.67545 4.54465 9.35385 4.90346C9.03226 5.26226 8.87146 5.74465 8.87146 6.35063C8.87146 6.98319 9.00036 7.46691 9.25817 7.80179C9.51864
                                8.13402 9.89605 8.30013 10.3904 8.30013C10.6482 8.30013 10.91 8.27356 11.1758 8.2204V7.00445H10.0715V5.97588Z" fill="#EE2316"
                            />
                        </svg>
                    </span>
                }
                {
                    player.inSubsitution > 0 &&
                    <span className="icon_f ic_thay_nguoi">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="10" fill="white" />
                            <path fillRule="evenodd" clipRule="evenodd" d="M12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706
                                7.02944 21 12 21ZM14.3143 11.9308V16.5H9.68577V11.9308H6.60005L12.0001 6.60001L17.4001 11.9308H14.3143Z" fill="#319C2F"
                            />
                        </svg>
                    </span>
                }
                {
                    player.outSubsitution > 0 &&
                    <span className="icon_f ic_thay_nguoi">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="10" fill="white" />
                            <path fillRule="evenodd" clipRule="evenodd" d="M12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21C16.9706 21 21 16.9706 21 12C21 7.02944
                                16.9706 3 12 3ZM9.68572 12.0692L9.68572 7.5L14.3143 7.5L14.3143 12.0692L17.4 12.0692L12 17.4L6.6 12.0692L9.68572 12.0692Z" fill="#E94633"
                            />
                        </svg>
                    </span>
                }
                {
                    player.redCard > 0 &&
                    <span className="icon_f ic_the_do">
                        <span className="Common_iconWrapper__3p9Oo">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="12" r="11" fill="white" />
                                <rect x="6" y="5" width="12" height="14" rx="2.21591" fill="#E94633" />
                            </svg>
                        </span>
                    </span>
                }
                {
                    player.yellowTurnRedCard > 0 &&
                    <span className="icon_f ic_the_vang_do">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="10" fill="white" />
                            <rect x="8" y="7" width="10" height="12" rx="1.72249" fill="#F9C91E" />
                            <rect x="6" y="5" width="10" height="12" rx="1.72249" fill="#E94633" />
                        </svg>
                    </span>
                }
                {
                    player.yellowCard > 0 && player.yellowTurnRedCard === 0 &&
                    <span className="icon_f ic_the_vang">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="11" fill="white" />
                            <rect x="6" y="5" width="12" height="14" rx="2.21591" fill="#F9C91E" />
                        </svg>
                    </span>
                }
            </>
        );
    };

    getIconGoalLineup = player => {
        const goalClass = 'number-goal';
        const goal = (
            <span className="icon_f ic_bong" style={{ marginRight: player.goals > 1 ? '7px' : '0px' }}>
                {this.getGoalsNumber(player.goals, goalClass)}
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="10" fill="white" />
                    <path d="M18.3662 5.64183C17.5328 4.80294 16.5411 4.13749 15.4484 3.68402C14.3557 3.23054 13.1838 2.99805 12.0005 3.00001C10.8169 2.99816
                        9.64478 3.23069 8.55181 3.68415C7.45884 4.13762 6.46675 4.803 5.63293 5.64183C2.12232 9.14687 2.12232 14.8522 5.63293 18.3582C6.46677
                        19.1972 7.45899 19.8628 8.55214 20.3162C9.6453 20.7697 10.8177 21.0021 12.0014 21C13.1846 21.0021 14.3565 20.7697 15.4492 20.3164C16.5419
                        19.8631 17.5337 19.1978 18.3671 18.3591C21.8777 14.854 21.8777 9.14867 18.3662 5.64183ZM17.6207 16.4959H15.6011L14.4684 18.7583C13.6783
                        19.0498 12.8428 19.1999 12.0005 19.2016C11.1566 19.2001 10.3196 19.0497 9.52801 18.7574L8.39802 16.5049H6.38565C5.64323 15.5832 5.14115
                        14.492 4.92432 13.329L6.59725 11.1008L5.50237 8.91308C5.85173 8.17088 6.32659 7.49438 6.90608 6.91328C7.71714 6.10047 8.71126 5.49338
                        9.80533 5.14278L11.9996 6.60486L14.1947 5.14368C15.2885 5.49453 16.2825 6.10124 17.094 6.91328C17.6728 7.49368 18.1474 8.16924 18.4968
                        8.91038L17.4019 11.1008L19.0748 13.329C18.859 14.4882 18.3595 15.5762 17.6207 16.4959Z" fill="#324A75"
                    />
                    <path d="M9 11.3077L10.2857 15H13.7143L15 11.3077L12 9L9 11.3077Z" fill="#324A75" />
                </svg>
            </span>
        );
        const penalty = (
            <span className="icon_f ic_penalty">
                {this.getGoalsNumber(player.penalty, goalClass)}
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="10" fill="white" />
                    <path d="M18.3663 5.64183C17.5329 4.80294 16.5411 4.13749 15.4484 3.68402C14.3557 3.23054 13.1838 2.99805 12.0005 3.00001C10.817 2.99816 9.64481
                        3.23069 8.55184 3.68415C7.45887 4.13762 6.46678 4.803 5.63296 5.64183C2.12235 9.14687 2.12235 14.8522 5.63296 18.3582C6.4668 19.1972 7.45902
                        19.8628 8.55217 20.3162C9.64533 20.7697 10.8177 21.0021 12.0014 21C13.1846 21.0021 14.3565 20.7697 15.4492 20.3164C16.5419 19.8631 17.5337
                        19.1978 18.3672 18.3591C21.8778 14.854 21.8778 9.14867 18.3663 5.64183ZM17.6207 16.4959H15.6012L14.4685 18.7583C13.6783 19.0498 12.8429 19.1999
                        12.0005 19.2016C11.1566 19.2001 10.3196 19.0497 9.52804 18.7574L8.39805 16.5049H6.38568C5.64326 15.5832 5.14118 14.492 4.92436 13.329L6.59728
                        11.1008L5.5024 8.91308C5.85176 8.17088 6.32662 7.49438 6.90611 6.91328C7.71717 6.10047 8.71129 5.49338 9.80536 5.14278L11.9996 6.60486L14.1948
                        5.14368C15.2885 5.49453 16.2825 6.10124 17.094 6.91328C17.6729 7.49368 18.1474 8.16924 18.4968 8.91038L17.4019 11.1008L19.0749 13.329C18.859
                        14.4882 18.3595 15.5762 17.6207 16.4959Z" fill="#324A75"
                    />
                    <path d="M9 11.3077L10.2857 15H13.7143L15 11.3077L12 9L9 11.3077Z" fill="#324A75" />
                    <circle cx="7" cy="7" r="5" fill="white" />
                    <path d="M6.76902 7.10984H7.19044C7.58432 7.10984 7.87905 7.03271 8.07461 6.87847C8.27017 6.72147 8.36795 6.49423 8.36795 6.19675C8.36795 5.89652
                        8.28532 5.67479 8.12006 5.53156C7.95755 5.38833 7.70139 5.31672 7.35158 5.31672H6.76902V7.10984ZM9.66115 6.1513C9.66115 6.80134 9.45732 7.29851
                        9.04967 7.64282C8.64477 7.98712 8.06772 8.15927 7.31852 8.15927H6.76902V10.3077H5.48822V4.26729H7.41768C8.15035 4.26729 8.70675 4.42567 9.08685
                        4.74242C9.46972 5.05643 9.66115 5.52605 9.66115 6.1513Z" fill="#324A75"
                    />
                </svg>
            </span>
        );
        let icon = '';
        if (player.goals > 0 && player.penalty > 0) {
            icon = <div className="lineup-goals">{goal} {penalty}</div>;
        } else if (player.goals > 0) {
            icon = goal;
        } else if (player.penalty > 0) {
            icon = penalty;
        }

        return icon;
    };

    renderListPlayer = (title, home, away) => {
        return (
            <div className="line-up-change-men block-line-up">
                <div className="block-line-up-main">
                    <div className="block-line-up-title">{title}</div>
                    <div className="block-line-up-body">
                        <div className="line-up">
                            <div className="line-up-table">
                                <div className="table-paticipant home-team">
                                    {
                                        home.map((homePlayer, hIndex) => {
                                            return (
                                                <ul key={hIndex} className="list-paticipant">
                                                    <li className="number-player">{homePlayer.playerNumber}</li>
                                                    <li className="name-player">{homePlayer.playerName}</li>
                                                    <li className="action-player">{this.renderIconListPlayer(homePlayer)}</li>
                                                </ul>
                                            );
                                        })
                                    }
                                </div>
                                <div className="table-paticipant away-team">
                                    {
                                        away.map((awayPlayer, aIndex) => {
                                            return (
                                                <ul key={aIndex} className="list-paticipant away-paticipant">
                                                    <li className="number-player">{awayPlayer.playerNumber}</li>
                                                    <li className="name-player">{awayPlayer.playerName}</li>
                                                    <li className="action-player">{this.renderIconListPlayer(awayPlayer)}</li>
                                                </ul>
                                            );
                                        })
                                    }
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    };

    renderPitchView = () => {
        const lineup = this.props.match.lineup;
        const matchInfo = this.props.match.generalInfo;
        if (!lineup.generalInfo || !lineup.generalInfo.homeFormation || !lineup.generalInfo.awayFormation) {
            return null;
        }
        const homeLineup = this.getListLineup(lineup, IncidentPosition.Home);
        let awayLineup = this.getListLineup(lineup, IncidentPosition.Away);
        awayLineup = awayLineup.reverse();
        return (
            <div className="line-up-map">
                <h2 className="title-custom">Đội hình</h2>

                <div className="line-up-map-main">
                    <div className="line-up-map-head">
                        <div className="team">
                            <div className="team-head-name">{CommonFunctions.getTeamName(matchInfo.homeTeam)}</div>
                            <div className="team-head-number">{lineup.generalInfo.homeFormation}</div>
                        </div>
                    </div>
                    <div className="line-up-map-body">
                        {this.renderTeamLineup(homeLineup, true)}
                        {this.renderTeamLineup(awayLineup, false)}
                    </div>
                    <div className="line-up-map-head">
                        <div className="team">
                            <div className="team-head-name">{CommonFunctions.getTeamName(matchInfo.awayTeam)}</div>
                            <div className="team-head-number">{lineup.generalInfo.awayFormation}</div>
                        </div>
                    </div>
                </div>
            </div>
        );
    };

    renderTeamLineup = (lineups, isHome) => {
        return (
            <div className="team-content">
                {
                    lineups.map((line, lineIndex) => {
                        return (
                            <div className="pitch-row" key={lineIndex}>
                                {
                                    line.map((player, pindex) => {
                                        return (
                                            <div className="pitch-item" key={pindex}>
                                                <div className="team-player-img">
                                                    <img src={Site.resolveClientUrl(`css/images/${isHome ? 'red.png' : 'blue.png'}`)} alt="" />
                                                    <div className="team-number">
                                                        {player.number}
                                                        {this.renderIconLineup(player)}
                                                    </div>
                                                </div>
                                                <div className="text-name">{player.name}</div>
                                            </div>
                                        );
                                    })
                                }
                            </div>
                        );
                    })
                }
            </div>
        );
    };


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
                const outPlayer = players.find(item => item.playerId === incident.outPlayerId);
                return (
                    <ul key={key} className="list-paticipant list-change-main">
                        <li className="number-player">{incident.timeShow}'</li>
                        <li className="name-player">
                            <p><i className="icon-livescore icon-in"></i> {inPlayer?.playerNumber || 0} - {incident.inPlayerName}</p>
                            <p> <i className="icon-livescore icon-out"></i> {outPlayer?.playerNumber || 0} - {incident.outPlayerName}</p>
                        </li>
                    </ul>
                );
            })
        );
    };

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
        );
    };



    render() {
        const { lineup } = this.props.match;
        if (!lineup || !lineup.generalInfo || (lineup.home.length === 0 && lineup.away.length === 0 )) {
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
                        <div className="line-up-change-men block-line-up perent-men">
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