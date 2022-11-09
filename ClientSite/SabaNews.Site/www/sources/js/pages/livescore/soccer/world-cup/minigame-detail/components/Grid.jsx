import React, { PureComponent } from 'react';
import Item from './Item';
import { WorldCup } from '@jsroot/common/constants';

export default class Grid extends PureComponent {
    constructor(props) {
        super(props);
        this.totalScore = 0;
    }

    getVotesByStage = stage => {
        const { votes } = this.props;
        return votes.filter(item => item.stage === stage);
    };

    getMatchByStage = stage => {
        const { matches } = this.props;
        const groupStage = matches.filter(item => item.stage.stageId === WorldCup.GroupsStage);
        switch(stage) {
            case WorldCup.MinigameStage.Stage1:
                return groupStage.slice(0, 16);
            case WorldCup.MinigameStage.Stage2:
                return groupStage.slice(16, 32);
            case WorldCup.MinigameStage.Stage3:
                return groupStage.slice(32);
            case WorldCup.MinigameStage.Stage4:
                return matches.filter(item => item.stage.stageId > WorldCup.GroupsStage);
            default:
                return [];
        }
    };

    render() {
        return (
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Giai đoạn</th>
                        <th>Tình trạng</th>
                        <th>Điểm</th>
                    </tr>
                </thead>
                <tbody>
                    <Item stage={WorldCup.MinigameStage.Stage4}
                        votes={this.getVotesByStage(WorldCup.MinigameStage.Stage4)}
                        matches={this.getMatchByStage(WorldCup.MinigameStage.Stage4)} />
                    <Item stage={WorldCup.MinigameStage.Stage3}
                        votes={this.getVotesByStage(WorldCup.MinigameStage.Stage3)}
                        matches={this.getMatchByStage(WorldCup.MinigameStage.Stage3)} />
                    <Item stage={WorldCup.MinigameStage.Stage2}
                        votes={this.getVotesByStage(WorldCup.MinigameStage.Stage2)}
                        matches={this.getMatchByStage(WorldCup.MinigameStage.Stage2)} />
                    <Item stage={WorldCup.MinigameStage.Stage1}
                        votes={this.getVotesByStage(WorldCup.MinigameStage.Stage1)}
                        matches={this.getMatchByStage(WorldCup.MinigameStage.Stage1)} />
                </tbody>
            </table>
            
        );
    }
}