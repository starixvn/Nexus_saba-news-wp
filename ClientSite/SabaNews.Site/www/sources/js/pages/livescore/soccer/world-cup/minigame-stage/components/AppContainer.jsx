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
        if(stage === WorldCup.MinigameStage.Stage1) {
            tmpMatches = groupStage.slice(0, 16);
        } else if (stage === WorldCup.MinigameStage.Stage2) {
            tmpMatches = groupStage.slice(16, 32);
        } else if(stage === WorldCup.MinigameStage.Stage3) {
            tmpMatches = groupStage.slice(32);
        } else if(stage === WorldCup.MinigameStage.Stage4) {
            tmpMatches = matches.filter(item => item.stage.stageId > WorldCup.GroupsStage);
        }
        return tmpMatches;
    };

    getNumberVoted = () => {
        const { votes } = this.props;
        const voted = votes.filter(item => item.stage == window.stage);
        return voted.length;
    }

    render() {
        const { matches, stages, league } = this.props;
        if(matches === null) {
            return null;
        }
        const showMatches = this.getMatchByStage(matches);
        return (
            <div className="livescore-soccer">
                <div className="Title">{`Giai đoạn ${window.stage}`}</div>
                <div className="Title">{`Số trận dự đoán ${this.getNumberVoted()}/${showMatches.length}`}</div>
                <Stages matches={showMatches} league={league} stages={stages}/>
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