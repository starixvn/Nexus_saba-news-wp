import React, { PureComponent } from 'react';
import CommonFunctions from '@jsroot/common/common-functions';
import { Tabs, Tab } from 'react-bootstrap';
import Grid from './Grid';
import { WorldCup } from '@jsroot/common/constants';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import Site from '@jsroot/common/site';

export class Stages extends PureComponent {
    constructor(props) {
        super(props);
        this.state = {
            selectedStageId: this.initStageId()
        };
    }

    initStageId = () => {
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

    handleClick = event => {
        const url = Site.resolveClientUrl(WorldCup.MinigameDetailUrl);
        if (event.ctrlKey) {
            window.open(url, '_blank');
        } else {
            window.location = url;
        }
    };


    render() {
        const { votes, groupStages } = this.props;
        const { selectedStageId } = this.state;
        const stages = groupStages;
        return (
            <div>
                <div className="statistic">chi tiết giai đoạn { window.stage } |<span onClick={this.handleClick}>Quay lại</span></div>
                {
                    stages.length === 1 &&
                    <Grid matches={stages[0].matches} votes={votes} />
                }
                {
                    stages.length > 1 &&
                    <Tabs className="live-score-nav" activeKey={selectedStageId} onSelect={this.handleSelectTab}>
                        {
                            stages.map(group => {
                                return (
                                    <Tab eventKey={group.key} title={CommonFunctions.getStageName(group.stage)}  key={group.key}  className="season-standings">
                                        <Grid matches={group.matches} votes={votes}/>
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
