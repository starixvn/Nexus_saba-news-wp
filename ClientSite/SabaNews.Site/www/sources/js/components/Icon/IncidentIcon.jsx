import React, { Component } from 'react';
import { IncidentPosition, IncidentStatus } from '@jsroot/common/constants';

export default class IncidentIcon extends Component {
    constructor(props) {
        super(props);
    }

    renderGoalIcon = () => {
        return (
            <i className="icon-livescore icon-ball" />
        );
    }

    renderOwnGoalIcon = () => {
        return (
            <i className="icon-livescore icon-ball" />
        );
    }

    renderPenaltyIcon = () => {
        return (
            <i className="icon-livescore icon-ball" />
        );
    }

    renderPenaltyMissedIcon = () => {
        return (
            <i className="icon-livescore icon-ball" />
        );
    }

    renderRedCardIcon = () => {
        return (
            <span className="icon-livescore icon-red-card"></span>
        );
    }

    renderYellowCardIcon = () => {
        return (
            <span className="icon-livescore icon-yellow-card"></span>
        );
    }

    renderYellowTurnRedCardIcon = () => {
        return (
            <div className="pr-30">
                <span className="ic-yellow-card"></span>
                <span className="ic-red-card"></span>
            </div>
        );
    }

    renderVARIcon = () => {
        return (
            <div>VAR</div>
        );
    }


    render() {
        const type =  this.props.type;
        let icon;
        switch(type) {
            case IncidentStatus.Goal:
                icon = this.renderGoalIcon();
                break;
            case IncidentStatus.OwnGoal:
                icon = this.renderOwnGoalIcon();
                break;
            case IncidentStatus.Penalty:
                icon = this.renderPenaltyIcon();
                break;
            case IncidentStatus.PenaltyMissed:
                icon = this.renderPenaltyMissedIcon();
                break;
            case IncidentStatus.RedCard:
                icon = this.renderRedCardIcon();
                break;
            case IncidentStatus.YellowCard:
                icon = this.renderYellowCardIcon();
                break;
            case IncidentStatus.YellowTurnRedCard:
                icon = this.renderYellowTurnRedCardIcon();
                break;
            case IncidentStatus.VAR:
                icon = this.renderVARIcon();
                break;
            default:
                icon = null;
        }
        return (
            <>
            {
                icon
            }
            </>
        );
    }
}



