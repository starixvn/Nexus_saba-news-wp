import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import Grid from './Grid';
import SkeletonGrid from './SkeletonGrid';
import SignalR from './SignalR';
import moment from 'moment';
import DateTimeService from '@jsroot/common/dateTimeService';
import { WorldCup } from '@jsroot/common/constants';
export class AppContainer extends Component {
    constructor(props) {
        super(props);
    }

    componentDidMount() {
        this.props.getMatches();
    }

    checkIsFinished = league => {
        const today = moment(DateTimeService.getCurrentClientDateTime()).format('YYYY-MM-DD HH:mm:ss');
        const endTime = moment(DateTimeService.convertFromUTC(league.season.endTime)).format('YYYY-MM-DD HH:mm:ss');
        return moment(today).isSameOrAfter(endTime);
    };

    getMatches = () => {
        const { league, matches } = this.props;
        if (!matches) {
            return null;
        }
        let tmpMatches = matches;
        if (this.checkIsFinished(league)) {
            tmpMatches = [...matches].reverse();
        } else if (league.currentStageId < WorldCup.QuarterfinalsStage) {
            tmpMatches = matches.filter(item => item.stage?.stageId < WorldCup.QuarterfinalsStage);
        } else if (league.currentStageId >= WorldCup.QuarterfinalsStage) {
            tmpMatches = matches.filter(item => item.stage.stageId !== WorldCup.GroupsStage);
        }
        return tmpMatches;
    };

    render() {
        const { league, matches } = this.props;
        const matchGrid = this.getMatches();
        return (
            <div>
                <SignalR />
                {
                    matches === null &&
                    <SkeletonGrid />
                }
                {
                    matchGrid && matchGrid.length > 0 &&
                    <Grid matches={matchGrid} league={league} isFinished={this.checkIsFinished(league)} />
                }
            </div>
        );
    }

}


const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { matches, league } = state;
    return { matches, league };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);