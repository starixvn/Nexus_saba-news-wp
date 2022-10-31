import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import { groupMatchesByStage, getMinigameStageStartTime } from '../../common.functions';
import DateTimeService from '@jsroot/common/dateTimeService';
import moment from 'moment';
import Site from '@jsroot/common/site';
import { WorldCup } from '@jsroot/common/constants';
import Stages from './Stages';

export class AppContainer extends Component {
    constructor(props) {
        super(props);
    }
    componentDidMount() {
        const startTime = getMinigameStageStartTime(parseInt(window.stage));
        const currentTime = moment(DateTimeService.getCurrentClientDateTime());
        if(startTime.isAfter(currentTime)) {
            window.location = Site.resolveClientUrl(WorldCup.MinigameUrl);
        } else {
            this.props.getMatches();
            this.props.getVotes();
        }
    }

    render() {
        const { matches } = this.props;
        if(matches === null ) {
            return null;
        }
        const stages = groupMatchesByStage(matches);
        return (
            <div className="livescore-soccer">
                <Stages groupStages={stages}/>
            </div>
        )
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { matches } = state;
    return { matches};
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);