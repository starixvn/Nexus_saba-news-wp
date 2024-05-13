import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import Tab from 'react-bootstrap/Tab';
import Tabs from 'react-bootstrap/Tabs';
import CommonFunctions from '@jsroot/common/common-functions';
import { MatchStatus } from '@jsroot/common/constants';
import LiveScore from './LiveScore';

export class AppContainer extends Component {
    constructor(props) {
        super(props);
    }

    componentDidMount() {
        this.props.getTeamData(window.teamId);
    }
    groupMatchesByStageAndRound = matches => {
        const totalItems = matches?.length;
        const tempMatches = [];
        for (let i = 0; i < totalItems; i++) {
            const match = { ...matches[i] };
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
    };

    groupMatches = matches => {
        const tempMatches = this.groupMatchesByStageAndRound(matches);
        const totalItems = tempMatches.length;
        const result = [];
        tempMatches.forEach((item, index) => {
            let isFinished = false;
            const stageMatches = item.matches;
            const lastMatch = stageMatches[stageMatches.length - 1];
            if(lastMatch.status === MatchStatus.Finished) {
                isFinished = true;
            }
            tempMatches[index] = {...item, isFinished};
        });
        for (let i = 0; i < totalItems; i++) {
            const item = tempMatches[i];
            let text;
            if (!item.stage) {
                text = '';
            }  else {
                text = CommonFunctions.getStageName(item.stage);
            }

            result.push({
                key: i,
                text,
                isFinished : item.isFinished,
                childs: CommonFunctions.groupMatchesByDate(item.matches)
            });
        }


        return result;
    };

    render() {
        const { matches, players, team } = this.props;
        const groups = this.groupMatches(matches);
        console.log(groups)
        return (
            <div className="block-info-team">
                <div className="container">
                    <Tabs
                        defaultActiveKey="livescore"
                        >
                        <Tab eventKey="livescore" title="livescore">
                            <LiveScore matches={groups}/>
                        </Tab>
                        <Tab eventKey="result" title="result">
                            result
                        </Tab>
                        <Tab eventKey="player" title="player">
                            player
                        </Tab>
                        <Tab eventKey="history" title="history">
                            Tiểu sử
                        </Tab>
                    </Tabs>
                </div>
            </div>
        )
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { matches, players, team } = state;
    return { matches, players, team };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);