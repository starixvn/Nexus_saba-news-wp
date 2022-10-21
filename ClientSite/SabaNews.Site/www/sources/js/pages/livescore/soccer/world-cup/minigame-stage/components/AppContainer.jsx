import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import Stages from './Stages';
import { getMatchByEventStage } from '../../common.functions';

export class AppContainer extends Component {
    constructor(props) {
        super(props);
    }
    componentDidMount() {
        this.props.getMatches();
        this.props.getVotes();
    }


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
        const showMatches = getMatchByEventStage(matches);
        return (
            <div className="livescore-soccer">
                <div className="statistic">{`Giai đoạn ${window.stage}`}</div>
                <div className="statistic">{`Số trận dự đoán ${this.getNumberVoted()}/${showMatches.length}`}</div>
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