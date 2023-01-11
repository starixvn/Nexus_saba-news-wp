import React, { PureComponent } from 'react';
import CommonFunctions from '@jsroot/common/common-functions';
import Item from '../../../league-detail/components/standings/grid/index';

export  default class Grid extends PureComponent {
    constructor(props) {
        super(props);

    }

    groupStandingsByGroupName = seasonStandings => {
        const totalItems = seasonStandings.length;
        const standings = [];
        for (let i = 0; i < totalItems; i++) {
            const temp = seasonStandings[i];
            const key = temp.groupName;
            const item = {
                key,
                childs: [temp]
            };
            if (standings.length === 0) {
                standings.push(item);
            } else {
                const index = standings.findIndex(x => x.key === key);
                if (index === - 1) {
                    standings.push(item);
                } else {
                    standings[index].childs.push(temp);
                }
            }
        }

        return standings;
    };

    groupStandingsByStage = seasonStandings => {
        let totalItems = seasonStandings.length;
        const standings = [];
        for (let i = 0; i < totalItems; i++) {
            const temp = { ...seasonStandings[i] };
            temp.promotion = JSON.parse(temp.promotion);
            temp.promotion = temp.promotion && temp.promotion.Color ? temp.promotion : null;
            const item = {
                stageId: temp.stageId,
                stageNameEN: temp.stageNameEN,
                stageNameVN: temp.stageNameVN,
                orderIndex: temp.orderIndex,
                childs: [temp]
            };
            if (standings.length === 0) {
                standings.push(item);
            } else {
                const index = standings.findIndex(x => x.stageId === temp.stageId);
                if (index === - 1) {
                    standings.push(item);
                } else {
                    standings[index].childs.push(temp);
                }
            }
        }

        totalItems = standings.length;
        for (let i = 0; i < totalItems; i++) {
            standings[i].childs = this.groupStandingsByGroupName(standings[i].childs);
        }

        return standings;
    };

    render() {
        const standing = this.groupStandingsByStage(this.props.leagueStanding.standing)
        const lastStanding = standing[standing.length - 1];
        return (
            <div>
                <Item standings={lastStanding.childs} isMobile={true} />
            </div>
        );
    }
}