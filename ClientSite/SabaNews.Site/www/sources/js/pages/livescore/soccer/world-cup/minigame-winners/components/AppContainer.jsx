import { connect } from 'react-redux';
import { ActionCreators } from '../actions';
import LangHelper from '@jsroot/common/langHelper';
import React, { Component } from 'react';
import { bindActionCreators } from 'redux';
import { getMatchesByStage } from '../../common.functions';
import { MobileMaxWidth } from '@jsroot/common/constants';
import Winner from '../components/Winner';
import DateTimeService from '@jsroot/common/dateTimeService';
import moment from 'moment';

export class AppContainer extends Component {
    constructor(props) {
        super(props);
        this.state = {
            isMobile: window.innerWidth <= MobileMaxWidth ? true : false,
            stages:[{name:LangHelper.getResource('Stage1'), value:1},
            {name:LangHelper.getResource('Stage2'), value:2},
            {name:LangHelper.getResource('Stage3'), value:3},
            {name:LangHelper.getResource('Stage4'), value:4}],
            selectedStage: this.handleSelectedStageWinner()
        };
    }

    handleSelectedStageWinner (){
        let result = 0;
        const maxCountStage = 4;
        const timeEnd = [
            moment(new Date(2022, 10, 8, 15, 0, 0)),
            moment(new Date(2022, 10, 11, 13, 30, 0)),
            moment(new Date(2022, 11, 11, 15, 0, 0)),
            moment(new Date(2022, 11, 30, 8, 0, 0))
        ];
        for(let i = 0; i < maxCountStage; i++){
            const startTime = timeEnd[i];
            const currentTime = moment(DateTimeService.getCurrentClientDateTime());
            const lastMinutes = startTime.diff(currentTime, 'minutes');
            if( lastMinutes <= 30) {
                result = i + 1;
            }
        }
        return result;
    }

    componentDidMount() {
        this.props.getMatches();
        this.props.getVotes(this.state.selectedStage);
        window.stage = this.state.selectedStage;
    }

    handleChange = stage => {
        if(stage !== this.state.selectedStage){
            this.setState({
                selectedStage: stage
            });
            this.props.getVotes(parseInt(stage, 10));
        }
    };

    handleChangeSelect = event => {
        this.setState({
            selectedStage: event.target.value
        });
        this.props.getVotes(parseInt(event.target.value, 10));
    };

    render() {
        const { matches, votes } = this.props;
        const { isMobile } = this.state;
        if(matches === null ) {
            return null;
        }
        const countStageWinners =this.state.stages.filter( x => x.value <= window.CountStageEnd);
        const showMatches = getMatchesByStage(matches, parseInt(this.state.selectedStage));
        return (
            <>
                {
                    countStageWinners.length > 0 && <div className="minigame-winner">
                        <div className="wraper-minigame-table">
                            <div className="minigame-title winner-title">
                                <h1 className="text">
                                    {LangHelper.getResource('WinnersMinigame')}
                                </h1>
                                <div className="dots-parent">
                                    <ul className="dots">
                                        <li className="dots-small"></li>
                                        <li className="dots-regular"></li>
                                        <li className="dots-parent-center"></li>
                                        <li className="dots-regular"></li>
                                        <li className="dots-small"></li>
                                    </ul>
                                    <span className="line line-left"></span>
                                    <span className="line line-right"></span>
                                </div>
                            </div>
                            <div className="minigame-control">
                                {
                                    !isMobile && countStageWinners.map( item =>
                                        <button className= {parseInt(this.state.selectedStage) === item.value ? 'buttonStage active'
                                        : 'buttonStage'} onClick={() => this.handleChange(item.value)}>{item.name}</button>
                                    )
                                }
                                {
                                    isMobile &&
                                    <select value={parseInt(this.state.selectedStage)}
                                        onChange={this.handleChangeSelect} className="custom-select">
                                        {
                                            countStageWinners.map( index =>
                                            <option key={index.value} value={index.value}>{index.name}</option> )
                                        }
                                    </select>
                                }
                            </div>
                            <Winner matches={showMatches} votes={votes} selectedStage={this.state.selectedStage} />
                        </div>
                        <div className="winner-icon">
                            <img src="../../../../css/images/winner_sticker_4.gif" alt="" />
                        </div>
                    </div>
                }
            </>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { matches, stages, league, votes } = state;
    return { matches, stages, league, votes };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);