import React, { PureComponent } from 'react';
import CommonFunctions from '@jsroot/common/common-functions';
import { Tabs, Tab } from 'react-bootstrap';
import Grid from './Grid';
import { WorldCup, MobileMaxWidth } from '@jsroot/common/constants';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';

export class Stages extends PureComponent {
    constructor(props) {
        super(props);
        this.state = {
            isMobile: window.innerWidth <= MobileMaxWidth ? true : false,
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

    componentWillUnmount() {
        window.removeEventListener('resize', this.handleResize);
    }


    componentDidMount() {
        window.addEventListener('resize', this.handleResize);
    }

    handleResize = () => {
        const isMobile = this.state.isMobile;
        if (!isMobile && window.innerWidth <= MobileMaxWidth) {
            this.setState({ isMobile: true });
        } else {
            if (isMobile && window.innerWidth > MobileMaxWidth) {
                this.setState({ isMobile: false });
            }
        }
    };

    handleSelectTab = key => {
        this.setState({ selectedStageId: parseInt(key) });
    };

    onChange = event => {
        this.setState({ selectedStageId: event.target.value });
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

    getMatchWithStage = () => {
        const { groupStages } = this.props;
        const { selectedStageId } = this.state;
        const stage = groupStages.find(item => item.key === parseInt(selectedStageId));
        return stage;
    };


    render() {
        const { groupStages } = this.props;
        const { selectedStageId, isMobile } = this.state;
        const stages = groupStages;
        const currentStage = this.getMatchWithStage();
        return (
            <>
                <div className="minigame-stage-container">
                    <div className="statistic">
                        <div className="statistic-sticky">
                            <div className="statistic_stage">{`Giai đoạn ${window.stage}`}</div>
                            <div className="statistic_round">{`Số trận đã dự đoán:`} <span className="number-big">{ this.getNumberVoted() }</span></div>
                        </div>
                    </div>
                    <div className="statistic-main">
                        {
                            (stages.length === 1) &&
                            <Grid matches={stages[0].matches} />
                        }
                        {
                            (stages.length > 1 && !isMobile) &&
                            <Tabs className="minigame-nav" activeKey={selectedStageId} onSelect={this.handleSelectTab}>
                                {
                                    stages.map(group => {
                                        return (
                                            <Tab eventKey={group.key} title={CommonFunctions.getStageName(group.stage)}  key={group.key}  className="season-standings">
                                                {selectedStageId === group.key && <Grid matches={group.matches}/>}
                                            </Tab>
                                        );
                                    })
                                }
                            </Tabs>
                        }
                        {
                            (stages.length > 1 && isMobile) &&
                            <>
                                <div className="nav-select-mb">
                                    <select onChange={this.onChange} value={selectedStageId} className="custom-select minigame-nav-mobile">
                                        {
                                            stages.map(group =>
                                                <option value={group.key} key={group.key} >{CommonFunctions.getStageName(group.stage)}</option>)
                                        }
                                    </select>
                                </div>
                                {
                                    <Grid matches={currentStage.matches} />
                                }
                            </>
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
