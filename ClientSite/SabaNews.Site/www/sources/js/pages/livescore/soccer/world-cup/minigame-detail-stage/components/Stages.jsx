import React, { PureComponent } from 'react';
import CommonFunctions from '@jsroot/common/common-functions';
import { Tabs, Tab } from 'react-bootstrap';
import Grid from './Grid';
import { WorldCup, MobileMaxWidth } from '@jsroot/common/constants';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import Site from '@jsroot/common/site';

export class Stages extends PureComponent {
    constructor(props) {
        super(props);
        this.state = {
            isMobile: window.innerWidth <= MobileMaxWidth ? true : false,
            selectedStageId: this.initStageId()
        };
    }

    componentWillUnmount() {
        window.removeEventListener('resize', this.handleResize);
    }

    componentDidMount() {
        window.addEventListener('resize', this.handleResize);
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

    handleResize = () => {
        const isMobile = this.state.isMobile;
        if (!isMobile && window.innerWidth <= MobileMaxWidth){
            this.setState({ isMobile: true });
        } else{
            if (isMobile && window.innerWidth > MobileMaxWidth){
                this.setState({ isMobile: false });
            }
        }
    };

    handleSelectTab = key => {
        this.setState({ selectedStageId: key });
    };

    onChange = event => {
        this.setState({ selectedStageId: parseInt(event.target.value, 10) });
    };

    handleClick = event => {
        const url = Site.resolveClientUrl(WorldCup.MinigameDetailUrl);
        if (event.ctrlKey) {
            window.open(url, '_blank');
        } else {
            window.location = url;
        }
    };

    getMatchWithStage = () => {
        const { groupStages } = this.props;
        const { selectedStageId } = this.state;
        const stage = groupStages.find(item => item.key === parseInt(selectedStageId, 10));
        return stage;
    };


    render() {
        const { votes, groupStages } = this.props;
        const { selectedStageId, isMobile } = this.state;
        const stages = groupStages;
        const currentStage = this.getMatchWithStage();
        return (
            <>
                <ul className="stage-number">
                    <li>chi tiết giai đoạn { window.stage }</li>
                    <li onClick={this.handleClick}><i className="icon-go_back" />Quay lại</li>
                </ul>
                <div className="wrapper-scroll">
                    {
                        stages.length === 1 &&
                        <Grid matches={stages[0].matches} votes={votes} />
                    }
                    {
                        (stages.length > 1 && !isMobile) &&
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
                                <Grid matches={currentStage.matches}  votes={votes}/>
                            }
                        </>
                    }
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
