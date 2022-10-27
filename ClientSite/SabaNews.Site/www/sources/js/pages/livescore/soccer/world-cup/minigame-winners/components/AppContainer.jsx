import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import { getMatchByEventStage } from '../../common.functions';
import LangHelper from '@jsroot/common/langHelper';
import Winner from '../components/Winner';

export class AppContainer extends Component {
    constructor(props) {
        super(props);
        this.state = {
            stages:[{name:"Giai đoạn 1", value:1},
            {name:"Giai đoạn 2", value:2},
            {name:"Giai đoạn 3", value:3},
            {name:"Giai đoạn 4", value:4}],
            selectedStage: 1,
            search: ""
        }
    }

    componentDidMount() {
        this.props.getMatches();
        this.props.getVotes(this.state.selectedStage);
        window.stage = this.state.selectedStage;
    }

    handleChange = stage => {
        if(stage != this.state.selectedStage){
            this.setState({
                selectedStage: stage
            });
            this.props.getVotes(parseInt(stage));
        }
    };

    render() {
        const { matches, votes } = this.props;
        if(matches === null ) {
            return null;
        }
        const showMatches = getMatchByEventStage(matches);
        return (
            <div className="minigame-winner">
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
                    <div className="minigame-winner-control">
                        {
                                this.state.stages.map( index => 
                                    <button onClick={e => this.handleChange(index.value)}>{index.name}</button>
                                )
                        }
                    </div>
                    <Winner matches={showMatches} votes={votes} search={this.state.search} />
                </div>
            </div>
        )
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { matches, stages, league, votes } = state;
    return { matches, stages, league, votes };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);