import React, { PureComponent } from 'react';
import CommonFunctions from '@jsroot/common/common-functions';
import { Tabs, Tab } from 'react-bootstrap';
import moment from 'moment';
import DateTimeService from '@jsroot/common/dateTimeService';
import Grid from './Grid';

export default class Stages extends PureComponent {
    constructor(props) {
        super(props);
        this.state = {
            selectedStageId: this.initStageId()
        }
    }

    initStageId = () => {
        const { league, stages } = this.props;
        let stageId = league.currentStageId;
        const today = moment(DateTimeService.getCurrentClientDateTime()).format('YYYY-MM-DD HH:mm:ss');
        const endTime = moment(DateTimeService.convertFromUTC(league.season.endTime)).format('YYYY-MM-DD HH:mm:ss');
        if(stageId === 0 || moment(today).isSameOrAfter(endTime)) {
            stageId = stages[stages.length - 1].key;
        }
        return stageId;
    };

    handleSelectTab = key => {
        this.setState({ selectedStageId: key });
    };


    groupMatchesByStage = matches => {
        let totalItems = matches.length;
        const tempMatches = [];
        for (let i = 0; i < totalItems; i++) {
            const match = matches[i];
            const stage = match.stage;
            const key = stage ? stage.stageId : 0;
            const item = {
                key,
                stage,
                matches: [match]
            };

            if (tempMatches.length === 0) {
                tempMatches.push(item);
            } else {
                const index = tempMatches.findIndex(x => x.key === key);
                if (index === -1) {
                    tempMatches.push(item);
                } else {
                    tempMatches[index].matches.push(match);
                }
            }
        }
        return tempMatches;
    }


    render() {
        const { matches, league } = this.props;
        const { selectedStageId } = this.state;
        console.log(selectedStageId);
        const stages = this.groupMatchesByStage(matches);
        return (
            <div>
                {
                    stages.length === 1 &&
                    <Grid matches={stages[0].matches} />
                }
                {
                    stages.length > 1 &&
                    <Tabs className="live-score-nav" activeKey={selectedStageId} onSelect={this.handleSelectTab}>
                        {
                            stages.map(group => {
                                return (
                                    <Tab eventKey={group.key} title={CommonFunctions.getStageName(group.stage)}  key={group.key}  className="season-standings">
                                        <Grid matches={group.matches}/>
                                    </Tab>
                                );
                            })
                        }
                    </Tabs>
                }
            </div>
        );
    }
}
