import React, { PureComponent } from 'react';
import CommonFunctions from '@jsroot/common/common-functions';
import { Tabs, Tab } from 'react-bootstrap';
import Grid from './Grid';
import { WorldCup } from '@jsroot/common/constants';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';

export class Stages extends PureComponent {
    constructor(props) {
        super(props);
        this.state = {
            selectedStageId: this.initDefaultStage()
        }
    }

    initDefaultStage = () => {
        const { league, stages } = this.props;
        const knockoutStages = stages.filter(item => item.stageId !== WorldCup.GroupsStage);
        let stageId = league.currentStageId;
        if(stageId === 0 || stageId === WorldCup.GroupsStage) {
            stageId = knockoutStages[0].stageId;
        }
        return stageId;
    };

    handleSelectTab = key => {
        this.setState({ selectedStageId: key });
    };


    getNumberVoted = () => {
        const { votes, groupStages } = this.props;
        let count = 0;
        const voted = votes.filter(item => item.stage == window.stage);
        groupStages.map(group => {
            count += group.matches.length;
        });
        return `${voted.length}/${count}`;
    };


    render() {
        const { groupStages } = this.props;
        const { selectedStageId } = this.state;
        const stages = groupStages;
        return (
            <div>
                <div className="statistic">{`Giai đoạn ${window.stage}`}</div>
                <div className="statistic">{`Số trận dự đoán ${this.getNumberVoted()}`}</div>
                {
                    stages.length === 1 &&
                    <Grid matches={stages[0].matches} />
                }
                {
                    stages.length > 1 &&
                    <Tabs className="live-score-nav" activeKey={selectedStageId} onSelect={this.handleSelectTab}>
                        {
                            stages.map(group => {
                                return (
                                    <Tab eventKey={group.key} title={CommonFunctions.getStageName(group.stage)}  key={group.key}  className="season-standings">
                                        <Grid matches={group.matches}/>
                                    </Tab>
                                );
                            })
                        }
                    </Tabs>
                }
            </div>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { stages, league, votes } = state;
    return { stages, league, votes };
};

export default connect(mapStateToProps, mapDispatchToProps)(Stages);
