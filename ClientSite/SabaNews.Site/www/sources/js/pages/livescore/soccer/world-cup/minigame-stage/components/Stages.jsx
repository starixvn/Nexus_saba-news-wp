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
            <>
                <div className="minigame-stage-container">
                    <div className="statistic">
                        <div className="statistic-sticky">
                            <div className="statistic_stage">{`Giai đoạn ${window.stage}`}</div>
                            <div class="dots-parent">
                                <ul>
                                    <li class="dots-parent-center"></li>
                                    <li class="dots-regular"></li>
                                    <li class="dots-small"></li>
                                </ul>
                            </div>
                            <div className="statistic_round">{`Số trận đã dự đoán:`} <span className="number-big">{ this.getNumberVoted() }</span></div>
                        </div>
                    </div>
                    <div className="statistic-main">
                        {
                            stages.length === 1 &&
                            <Grid matches={stages[0].matches} />
                        }
                        {
                            stages.length > 1 &&
                            <Tabs className="minigame-nav" activeKey={selectedStageId} onSelect={this.handleSelectTab}>
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
                </div>
            </>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { stages, league, votes } = state;
    return { stages, league, votes };
};

export default connect(mapStateToProps, mapDispatchToProps)(Stages);
