import { bindActionCreators } from 'redux';
import Standing from '../components/Standing';
import React, { Component } from 'react';
import { ActionCreators } from '../actions';
import { connect } from 'react-redux';
import { getMatchByEventStage } from '../../common.functions';
import LangHelper from '@jsroot/common/langHelper';

export class AppContainer extends Component {
    constructor(props) {
        super(props);
        this.state = {
            selectedStage: 1,
            stages:[{name:LangHelper.getResource('Stage1'), value:1},
            {name:LangHelper.getResource('Stage2'), value:2},
            {name:LangHelper.getResource('Stage3'), value:3},
            {name:LangHelper.getResource('Stage4'), value:4}]
        };
    }

    handleChange = event => {
        this.setState({
            selectedStage: event.target.value
        });
        this.props.getVotes(parseInt(event.target.value, 10));
    };

    componentDidMount() {
        this.props.getMatches();
        this.props.getVotes(this.state.selectedStage);
        window.stage = this.state.selectedStage;
    }

    render() {
        const { matches, votes } = this.props;
        if(matches === null ) {
            return null;
        }
        const showMatches = getMatchByEventStage(matches);
        return (
            <>
                {
                    <div className="minigame-section minigame-standing">
                        <div className="wraper-minigame-table">
                            <div className="minigame-title standing-title">
                                <h1 className="text">
                                    {LangHelper.getResource('StandingMinigame')}
                                </h1>
                                <div className="dots-parent">
                                    <ul className="dots">
                                        <li className="dots-small"></li>
                                        <li className="dots-regular"></li>
                                        <li className="dots-parent-center"></li>
                                        <li className="dots-regular"></li>
                                        <li className="dots-small"></li>
                                    </ul>
                                </div>
                            </div>
                            <div className="minigame-standing-control">
                                <select value={this.state.selectValue}
                                    onChange={this.handleChange} className="custom-select">
                                    {
                                        this.state.stages.map( index =>
                                        <option key={index.value} value={index.value}>{index.name}</option> )
                                    }
                                </select>
                            </div>
                            <Standing matches={showMatches} votes={votes} />
                        </div>
                        <div className="wave-standing-main"></div>
                        <div className="wave-standing-bg"></div>
                        <div className="section-icon">
                            <img src="../../../../css/images/icon_2.png" alt="" />
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