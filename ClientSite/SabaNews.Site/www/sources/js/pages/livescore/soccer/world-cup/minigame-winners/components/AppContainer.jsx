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
        this.setState({
            selectedStage: stage
        });
        this.props.getVotes(parseInt(stage));
    };

    render() {
        const { matches, votes } = this.props;
        if(matches === null ) {
            return null;
        }
        const showMatches = getMatchByEventStage(matches);
        return (
            <div className="minigame-section minigame-standing">
                <div className="wraper-minigame-table">
                    <div class="minigame-title standing-title">
                        <h1 class="text">
                            {LangHelper.getResource('WinnersMinigame')}
                        </h1>
                        <div class="dots-parent">
                            <ul className="dots-standing">
                                <li class="dots-small"></li>
                                <li class="dots-regular"></li>
                                <li class="dots-parent-center"></li>
                                <li class="dots-regular"></li>
                                <li class="dots-small"></li>
                            </ul>
                        </div>
                    </div>
                    <div className="minigame-standing-control">
                        {
                                this.state.stages.map( index => 
                                    <button onClick={e => this.handleChange(index.value)}>{index.name}</button>
                                )
                        }
                    </div>
                    <Winner matches={showMatches} votes={votes} search={this.state.search} />
                </div>
                <div className="wave-standing-main"></div>
                <div className="section-icon">
                    <img src="../../../../css/images/icon_2.png" alt="" />
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