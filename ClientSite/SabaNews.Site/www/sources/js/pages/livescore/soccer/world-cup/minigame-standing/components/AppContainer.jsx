import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import { getMatchByEventStage } from '../../common.functions';
import LangHelper from '@jsroot/common/langHelper';
import Standing from '../components/Standing';

export class AppContainer extends Component {
    constructor(props) {
        super(props);
        this.state = {
            stages:[{name:"Giai Đoạn 1", value:1},
            {name:"Giai Đoạn 2", value:2},
            {name:"Giai Đoạn 3", value:3},
            {name:"Giai Đoạn 4", value:4}],
            selectedStage: 1,
            search: ""
        }
    }

    componentDidMount() {
        this.props.getMatches();
        this.props.getVotes(this.state.selectedStage);
        window.stage = this.state.selectedStage;
    }

    handleChange = event => {
        this.setState({
            selectedStage: event.target.value
        });
        this.props.getVotes(parseInt(event.target.value));
    };

    render() {
        const { matches, votes } = this.props;
        if(matches === null ) {
            return null;
        }
        const showMatches = getMatchByEventStage(matches);
        return (
            <>
                <div className="minigame-section minigame-standing">
                    <div className="wraper-minigame-table">
                        <div class="minigame-title">
                            <h1 class="text">
                                {LangHelper.getResource('StandingMinigame')}
                            </h1>
                            <div class="dots-parent">
                                <ul>
                                    <li class="dots-small"></li>
                                    <li class="dots-regular"></li>
                                    <li class="dots-parent-center"></li>
                                    <li class="dots-regular"></li>
                                    <li class="dots-small"></li>
                                </ul>
                            </div>
                        </div>
                        <div className="minigame-standing-control">
                            <select value={this.state.selectValue} 
                                onChange={this.handleChange}>
                                {
                                    this.state.stages.map( index => 
                                    <option key={index.value} value={index.value}>{index.name}</option> )
                                }
                            </select>
                            <input placeholder="Tìm kiếm theo tên..." onChange={e => this.setState({search: e.target.value})}></input>
                        </div>
                        <Standing matches={showMatches} votes={votes} search={this.state.search} />
                    </div>
                    <div className="wave-standing-main"></div>
                </div>
            </>
        )
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { matches, stages, league, votes } = state;
    return { matches, stages, league, votes };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);