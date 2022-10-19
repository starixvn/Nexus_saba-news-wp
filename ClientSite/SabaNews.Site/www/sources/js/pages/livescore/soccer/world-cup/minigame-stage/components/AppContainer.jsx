import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import { WorldCup } from '@jsroot/common/constants';
import Stages from './Stages';

export class AppContainer extends Component {
    constructor(props) {
        super(props);
    }
    componentDidMount() {
        this.props.getMatches();
        this.props.getVotes();
    }

    getMatchByStage = matches => {
        let tmpMatches = [];
        const stage = parseInt(window.stage);
        const groupStage = matches.filter(item => item.stage.stageId === WorldCup.GroupsStage);
        if(stage === 1) {
            tmpMatches = groupStage.slice(0, 16);
        } else if (stage === 2) {
            tmpMatches = groupStage.slice(16, 32);
        } else if(stage === 3) {
            tmpMatches = groupStage.slice(32);
        } else if(stage === 4) {
            tmpMatches = matches.filter(item => item.stage.stageId > WorldCup.GroupsStage);
        }
        return tmpMatches;
    };

    render() {
        const {matches, league} = this.props;
        if(matches === null) {
            return null;
        }
        const showMatches = this.getMatchByStage(matches);
        return (
            <div className="livescore-soccer">
                <Stages matches={showMatches} league={league}/>
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