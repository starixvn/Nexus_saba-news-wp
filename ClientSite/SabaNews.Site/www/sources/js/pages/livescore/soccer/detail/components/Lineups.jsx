import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import { IncidentStatus, IncidentPosition } from '@jsroot/common/constants';
import Site from '@jsroot/common/site';
import LangHelper from '@jsroot/common/langHelper';
import CommonFunctions from '@jsroot/common/common-functions';

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
        return (
            <>
                {
                    playerIncident.goals > 0 &&
                    <>
                        <span className="icon-line-up">
                            {this.getGoalsNumber(playerIncident.goals, 'number-position')}
                            <i className="icon-livescore icon-ball" />
                        </span>
                    </>
                }
                {
                    playerIncident.ownGoals > 0 &&
                    <>
                        {this.getGoalsNumber(playerIncident.ownGoals, 'number-position')}
                        <span className="icon-line-up">
                            <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fillRule="evenodd" clipRule="evenodd" d="M8.71102 18.3582C7.16785 16.817 6.30302 14.851 6.11652 12.8336H8.37438L8.00241 13.329C8.21924 14.492 8.72132 15.5832 9.46374 16.5049H11.4761L12.6061 18.7574C13.3976 19.0497 14.2346 19.2001 15.0786 19.2016C15.9209 19.1999 16.7564 19.0498 17.5465 18.7583L18.6792 16.4959H20.6988C21.4375 15.5762 21.9371 14.4882 22.1529 13.329L20.48 11.1008L21.5749 8.91038C21.2255 8.16924 20.7509 7.49368 20.1721 6.91328C19.3606 6.10124 18.3666 5.49453 17.2728 5.14368L15.0777 6.60486L15.0102 6.55989C14.797 5.22349 14.0939 4.0442 13.0913 3.21989C13.7419 3.07307 14.4084 2.99897 15.0786 3.00001C16.2618 2.99805 17.4338 3.23054 18.5265 3.68402C19.6192 4.13749 20.6109 4.80294 21.4443 5.64183C24.9558 9.14867 24.9558 14.854 21.4452 18.3591C20.6117 19.1978 19.6199 19.8631 18.5273 20.3164C17.4346 20.7697 16.2627 21.0021 15.0795 21C13.8958 21.0021 12.7234 20.7697 11.6302 20.3162C10.5371 19.8628 9.54486 19.1972 8.71102 18.3582ZM13.3636 15L12.3608 12.1202C13.4655 11.4824 14.3251 10.4617 14.7615 9.24332L15.0778 9L18.0778 11.3077L16.7921 15H13.3636ZM6.4248 10.3832C6.96191 9.80135 7.23047 8.96883 7.23047 7.88566C7.23047 6.79951 6.9634 5.96699 6.42928 5.3881C5.89814 4.80922 5.12977 4.51978 4.12419 4.51978C3.1186 4.51978 2.34576 4.80773 1.80566 5.38363C1.26855 5.95654 1 6.78757 1 7.87671C1 8.96585 1.26855 9.80135 1.80566 10.3832C2.34277 10.9651 3.11263 11.256 4.11523 11.256C5.11784 11.256 5.88769 10.9651 6.4248 10.3832ZM2.87093 9.53727C2.59342 9.16726 2.45467 8.61673 2.45467 7.88566C2.45467 7.15459 2.59492 6.60406 2.87541 6.23405C3.1559 5.86106 3.57216 5.67456 4.12419 5.67456C5.22526 5.67456 5.7758 6.41159 5.7758 7.88566C5.7758 9.35674 5.22228 10.0923 4.11523 10.0923C3.5632 10.0923 3.14844 9.90728 2.87093 9.53727ZM13.1168 7.49178H10.5208V8.64656H11.7606V10.0117C11.4622 10.0714 11.1683 10.1012 10.8789 10.1012C10.3239 10.1012 9.90014 9.91474 9.60771 9.54175C9.31827 9.16577 9.17355 8.62269 9.17355 7.91252C9.17355 7.23218 9.35408 6.69059 9.71513 6.28776C10.0762 5.88493 10.5566 5.68351 11.1564 5.68351C11.6726 5.68351 12.1694 5.80287 12.6469 6.04158L13.1079 4.93156C12.4842 4.663 11.8367 4.52873 11.1653 4.52873C10.0941 4.52873 9.25561 4.82414 8.64987 5.41496C8.04711 6.00578 7.74573 6.82935 7.74573 7.88566C7.74573 8.97181 8.00534 9.80583 8.52454 10.3877C9.04673 10.9666 9.80167 11.256 10.7894 11.256C11.1773 11.256 11.5562 11.2277 11.9262 11.171C12.2992 11.1173 12.6961 11.0218 13.1168 10.8845V7.49178Z" fill="#EE2316" />
                            </svg>
                        </span>
                    </>
                }
                {
                    playerIncident.penalty > 0 &&
                    <>
                        {this.getGoalsNumber(playerIncident.penalty, 'number-position')}
                        <span className="icon-line-up">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.61549 7.76471H7.16084C7.67054 7.76471 8.05193 7.68427 8.305 7.5234C8.55807 7.35965 8.6846 7.12264 8.6846 6.81238C8.6846 6.49925 8.57767 6.26799 8.36381 6.11861C8.15351 5.96922 7.82203 5.89453 7.36936 5.89453H6.61549V7.76471ZM10.3581 6.76498C10.3581 7.44296 10.0943 7.96149 9.56678 8.32059C9.04282 8.67969 8.29609 8.85924 7.32658 8.85924H6.61549V11.1H4.95807V4.8H7.4549C8.40302 4.8 9.12302 4.96519 9.6149 5.29556C10.1103 5.62305 10.3581 6.11286 10.3581 6.76498Z" fill="#324A75" />
                                <path fillRule="evenodd" clipRule="evenodd" d="M2.99795 12.8336C3.18485 14.851 4.05154 16.817 5.59801 18.3582C6.43363 19.1972 7.42797 19.8628 8.52347 20.3162C9.61897 20.7697 10.7938 21.0021 11.9801 21C13.1658 21.0021 14.3402 20.7697 15.4353 20.3164C16.5303 19.8631 17.5242 19.1978 18.3595 18.3591C21.8776 14.854 21.8776 9.14867 18.3586 5.64183C17.5234 4.80294 16.5295 4.13749 15.4345 3.68402C14.3394 3.23054 13.165 2.99805 11.9792 3.00001C11.4075 2.99912 10.8384 3.05268 10.2797 3.15919C11.2622 3.99856 11.9049 5.22422 11.9725 6.60103L11.9783 6.60486L14.1781 5.14368C15.2742 5.49453 16.2704 6.10124 17.0836 6.91328C17.6637 7.49368 18.1392 8.16924 18.4894 8.91038L17.3922 11.1008L19.0687 13.329C18.8524 14.4882 18.3518 15.5762 17.6114 16.4959H15.5875L14.4524 18.7583C13.6606 19.0498 12.8233 19.1999 11.9792 19.2016C11.1335 19.2001 10.2947 19.0497 9.50143 18.7574L8.36902 16.5049H6.35235C5.60833 15.5832 5.10517 14.492 4.88789 13.329L5.26065 12.8336H2.99795ZM9.32278 12.3129L10.2605 15H13.6964L14.9848 11.3077L11.9784 9L11.8515 9.0974C11.5228 10.5042 10.5808 11.6751 9.32278 12.3129Z" fill="#324A75" />
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
        return (
            <>
                {
                    player.goals > 0 &&
                    <>
                        <span className="icon_f ic_bong">
                            {this.getGoalsNumber(player.goals, 'number-goal')}
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="12" r="10" fill="white" />
                                <path d="M18.3662 5.64183C17.5328 4.80294 16.5411 4.13749 15.4484 3.68402C14.3557 3.23054 13.1838 2.99805 12.0005 3.00001C10.8169 2.99816 9.64478 3.23069 8.55181 3.68415C7.45884 4.13762 6.46675 4.803 5.63293 5.64183C2.12232 9.14687 2.12232 14.8522 5.63293 18.3582C6.46677 19.1972 7.45899 19.8628 8.55214 20.3162C9.6453 20.7697 10.8177 21.0021 12.0014 21C13.1846 21.0021 14.3565 20.7697 15.4492 20.3164C16.5419 19.8631 17.5337 19.1978 18.3671 18.3591C21.8777 14.854 21.8777 9.14867 18.3662 5.64183ZM17.6207 16.4959H15.6011L14.4684 18.7583C13.6783 19.0498 12.8428 19.1999 12.0005 19.2016C11.1566 19.2001 10.3196 19.0497 9.52801 18.7574L8.39802 16.5049H6.38565C5.64323 15.5832 5.14115 14.492 4.92432 13.329L6.59725 11.1008L5.50237 8.91308C5.85173 8.17088 6.32659 7.49438 6.90608 6.91328C7.71714 6.10047 8.71126 5.49338 9.80533 5.14278L11.9996 6.60486L14.1947 5.14368C15.2885 5.49453 16.2825 6.10124 17.094 6.91328C17.6728 7.49368 18.1474 8.16924 18.4968 8.91038L17.4019 11.1008L19.0748 13.329C18.859 14.4882 18.3595 15.5762 17.6207 16.4959Z" fill="#324A75" />
                                <path d="M9 11.3077L10.2857 15H13.7143L15 11.3077L12 9L9 11.3077Z" fill="#324A75" />
                            </svg>
                        </span>
                    </>
                }
                {
                    player.ownGoals > 0 &&
                    <>
                        <span className="icon_f ic_ownbong">
                            {this.getGoalsNumber(player.ownGoals, 'number-goal')}
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="14" cy="12" r="10" fill="white" />
                                <path d="M20.3663 5.64183C19.5329 4.80294 18.5411 4.13749 17.4484 3.68402C16.3558 3.23054 15.1838 2.99805 14.0005 3.00001C12.817 2.99816 11.6449 3.23069 10.5519 3.68416C9.45891 4.13762 8.46681 4.80301 7.63299 5.64183C4.12238 9.14688 4.12238 14.8522 7.63299 18.3582C8.46683 19.1972 9.45905 19.8628 10.5522 20.3162C11.6454 20.7697 12.8177 21.0021 14.0014 21C15.1847 21.0021 16.3566 20.7697 17.4492 20.3164C18.5419 19.8631 19.5337 19.1978 20.3672 18.3591C23.8778 14.854 23.8778 9.14868 20.3663 5.64183ZM19.6208 16.496H17.6012L16.4685 18.7583C15.6784 19.0499 14.8429 19.1999 14.0005 19.2016C13.1566 19.2001 12.3196 19.0497 11.5281 18.7574L10.3981 16.505H8.38572C7.64329 15.5832 7.14121 14.492 6.92439 13.329L8.59731 11.1008L7.50244 8.91309C7.85179 8.17089 8.32665 7.49438 8.90614 6.91329C9.7172 6.10047 10.7113 5.49339 11.8054 5.14278L13.9996 6.60487L16.1948 5.14368C17.2885 5.49453 18.2825 6.10124 19.094 6.91329C19.6729 7.49369 20.1474 8.16925 20.4969 8.91039L19.402 11.1008L21.0749 13.329C20.8591 14.4882 20.3595 15.5762 19.6208 16.496Z" fill="#EE2316" />
                                <path d="M11.0001 11.3077L12.2858 15H15.7144L17.0001 11.3077L14.0001 8.99998L11.0001 11.3077Z" fill="#EE2316" />
                                <rect y="2" width="15" height="9" rx="3.87097" fill="white" />
                                <path d="M7.14075 6.32671C7.14075 7.2915 6.90155 8.03302 6.42314 8.5513C5.94474 9.06957 5.25902 9.3287 4.366 9.3287C3.47298 9.3287 2.78726 9.06957 2.30886 8.5513C1.83045 8.03302 1.59125 7.28884 1.59125 6.31874C1.59125 5.34864 1.83045 4.60844 2.30886 4.09814C2.78992 3.58518 3.47829 3.3287 4.37397 3.3287C5.26965 3.3287 5.95404 3.58651 6.42713 4.10213C6.90288 4.61774 7.14075 5.35927 7.14075 6.32671ZM2.88693 6.32671C2.88693 6.97787 3.01052 7.46824 3.25769 7.79781C3.50487 8.12738 3.8743 8.29216 4.366 8.29216C5.35205 8.29216 5.84507 7.63701 5.84507 6.32671C5.84507 5.01375 5.3547 4.35728 4.37397 4.35728C3.88228 4.35728 3.51151 4.52339 3.26168 4.85562C3.01185 5.18518 2.88693 5.67555 2.88693 6.32671Z" fill="#EE2316" />
                                <path d="M10.0715 5.97588H12.3838V8.99781C12.009 9.12007 11.6555 9.20512 11.3233 9.25296C10.9937 9.30346 10.6562 9.3287 10.3107 9.3287C9.43093 9.3287 8.7585 9.0709 8.29339 8.55528C7.83093 8.03701 7.5997 7.29415 7.5997 6.32671C7.5997 5.38585 7.86814 4.65229 8.40502 4.12605C8.94455 3.5998 9.69139 3.33668 10.6455 3.33668C11.2436 3.33668 11.8203 3.45628 12.3758 3.69548L11.9651 4.68419C11.5399 4.47156 11.0974 4.36525 10.6376 4.36525C10.1034 4.36525 9.67545 4.54465 9.35385 4.90346C9.03226 5.26226 8.87146 5.74465 8.87146 6.35063C8.87146 6.98319 9.00036 7.46691 9.25817 7.80179C9.51864 8.13402 9.89605 8.30013 10.3904 8.30013C10.6482 8.30013 10.91 8.27356 11.1758 8.2204V7.00445H10.0715V5.97588Z" fill="#EE2316" />
                            </svg>
                        </span>
                    </>
                }
                {
                    player.penalty > 0 &&
                    <>
                        <span className="icon_f ic_penalty">
                            {this.getGoalsNumber(player.penalty, 'number-goal')}
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="12" r="10" fill="white" />
                                <path d="M18.3663 5.64183C17.5329 4.80294 16.5411 4.13749 15.4484 3.68402C14.3557 3.23054 13.1838 2.99805 12.0005 3.00001C10.817 2.99816 9.64481 3.23069 8.55184 3.68415C7.45887 4.13762 6.46678 4.803 5.63296 5.64183C2.12235 9.14687 2.12235 14.8522 5.63296 18.3582C6.4668 19.1972 7.45902 19.8628 8.55217 20.3162C9.64533 20.7697 10.8177 21.0021 12.0014 21C13.1846 21.0021 14.3565 20.7697 15.4492 20.3164C16.5419 19.8631 17.5337 19.1978 18.3672 18.3591C21.8778 14.854 21.8778 9.14867 18.3663 5.64183ZM17.6207 16.4959H15.6012L14.4685 18.7583C13.6783 19.0498 12.8429 19.1999 12.0005 19.2016C11.1566 19.2001 10.3196 19.0497 9.52804 18.7574L8.39805 16.5049H6.38568C5.64326 15.5832 5.14118 14.492 4.92436 13.329L6.59728 11.1008L5.5024 8.91308C5.85176 8.17088 6.32662 7.49438 6.90611 6.91328C7.71717 6.10047 8.71129 5.49338 9.80536 5.14278L11.9996 6.60486L14.1948 5.14368C15.2885 5.49453 16.2825 6.10124 17.094 6.91328C17.6729 7.49368 18.1474 8.16924 18.4968 8.91038L17.4019 11.1008L19.0749 13.329C18.859 14.4882 18.3595 15.5762 17.6207 16.4959Z" fill="#324A75" />
                                <path d="M9 11.3077L10.2857 15H13.7143L15 11.3077L12 9L9 11.3077Z" fill="#324A75" />
                                <circle cx="7" cy="7" r="5" fill="white" />
                                <path d="M6.76902 7.10984H7.19044C7.58432 7.10984 7.87905 7.03271 8.07461 6.87847C8.27017 6.72147 8.36795 6.49423 8.36795 6.19675C8.36795 5.89652 8.28532 5.67479 8.12006 5.53156C7.95755 5.38833 7.70139 5.31672 7.35158 5.31672H6.76902V7.10984ZM9.66115 6.1513C9.66115 6.80134 9.45732 7.29851 9.04967 7.64282C8.64477 7.98712 8.06772 8.15927 7.31852 8.15927H6.76902V10.3077H5.48822V4.26729H7.41768C8.15035 4.26729 8.70675 4.42567 9.08685 4.74242C9.46972 5.05643 9.66115 5.52605 9.66115 6.1513Z" fill="#324A75" />
                            </svg>
                        </span>
                    </>
                }
                {
                    player.inSubsitution > 0 &&
                    <span className="icon_f ic_thay_nguoi">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="10" fill="white" />
                            <path fillRule="evenodd" clipRule="evenodd" d="M12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21ZM14.3143 11.9308V16.5H9.68577V11.9308H6.60005L12.0001 6.60001L17.4001 11.9308H14.3143Z" fill="#319C2F" />
                        </svg>
                    </span>
                }
                {
                    player.outSubsitution > 0 &&
                    <span className="icon_f ic_thay_nguoi">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="10" fill="white" />
                            <path fillRule="evenodd" clipRule="evenodd" d="M12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3ZM9.68572 12.0692L9.68572 7.5L14.3143 7.5L14.3143 12.0692L17.4 12.0692L12 17.4L6.6 12.0692L9.68572 12.0692Z" fill="#E94633" />
                        </svg>
                    </span>
                }
                {
                    (player.redCard > 0) &&
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
                    (player.yellowTurnRedCard > 0) &&
                    <span className="icon_f ic_the_vang_do">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="10" fill="white" />
                            <rect x="8" y="7" width="10" height="12" rx="1.72249" fill="#F9C91E" />
                            <rect x="6" y="5" width="10" height="12" rx="1.72249" fill="#E94633" />
                        </svg>
                    </span>
                }
                {
                    (player.yellowCard > 0 && player.yellowTurnRedCard === 0) &&
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
                                                );
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
                                                );
                                            })
                                        }
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </>
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
        const { lineup, generalInfo } = this.props.match;
        if (!CommonFunctions.checkEnableLineup(generalInfo, lineup)) {
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