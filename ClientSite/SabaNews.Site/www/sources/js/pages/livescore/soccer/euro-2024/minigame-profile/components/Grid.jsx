import React, { PureComponent } from 'react';
import Item from './Item';
import { Euro } from '../../euro-constants';
import { groupMatchesByStage } from '../../common.functions';
export default class Grid extends PureComponent {
    constructor(props) {
        super(props);
        this.totalScore = 0;
    }

    getVotesByStage = stage => {
        const { votes } = this.props;
        return votes.filter(item => item.stage === stage);
    };

    render() {
        return (
            <div className="wrapper-scroll">
               <div className="minigame-table">
                    <div className="minigame-table-flex minigame-table-header">
                        <div className="table-row table-row-index">#</div>
                        <div className="table-row table-row-name">Giai đoạn</div>
                        <div className="table-row table-row-score">Tình trạng</div>
                        <div className="table-row table-row-time">Điểm</div>
                    </div>
                    <div className="minigame-table-body">
                        <Item stage={Euro.MinigameStage.GroupsStage}
                            votes={this.getVotesByStage(Euro.MinigameStage.GroupsStage)}
                            matches={groupMatchesByStage(Euro.MinigameStage.GroupsStage)} />
                        <Item stage={Euro.MinigameStage.KnockoutStage}
                            votes={this.getVotesByStage(Euro.MinigameStage.KnockoutStage)}
                            matches={groupMatchesByStage(Euro.MinigameStage.KnockoutStage)}/>
                    </div>
                </div>
            </div>
        );
    }
}