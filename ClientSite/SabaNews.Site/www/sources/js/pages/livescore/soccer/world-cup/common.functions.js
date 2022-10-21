import React from 'react';
import { WorldCup } from '@jsroot/common/constants';

export  const getMatchByEventStage = matches => {
    let tmpMatches = [];
    const stage = parseInt(window.stage);
    const groupStage = matches.filter(item => item.stage.stageId === WorldCup.GroupsStage);
    if(stage === WorldCup.MinigameStage.Stage1) {
        tmpMatches = groupStage.slice(0, 16);
    } else if (stage === WorldCup.MinigameStage.Stage2) {
        tmpMatches = groupStage.slice(16, 32);
    } else if(stage === WorldCup.MinigameStage.Stage3) {
        tmpMatches = groupStage.slice(32);
    } else if(stage === WorldCup.MinigameStage.Stage4) {
        tmpMatches = matches.filter(item => item.stage.stageId > WorldCup.GroupsStage);
    }
    return tmpMatches;
};

export const groupMatchesByStage = matches => {
    const showMatches = getMatchByEventStage(matches);
    let totalItems = showMatches.length;
    const tempMatches = [];
    for (let i = 0; i < totalItems; i++) {
        const match = showMatches[i];
        const stage = match.stage;
        const key = stage ? stage.stageId : 0;
        const item = {
            key,
            stage,
            matches: [match]
        };

        if (tempMatches.length === 0) {
            tempMatches.push(item);
        } else {
            const index = tempMatches.findIndex(x => x.key === key);
            if (index === -1) {
                tempMatches.push(item);
            } else {
                tempMatches[index].matches.push(match);
            }
        }
    }
    return tempMatches;
}

const functions = {
    getMatchByEventStage,
    groupMatchesByStage
};
export default functions;
