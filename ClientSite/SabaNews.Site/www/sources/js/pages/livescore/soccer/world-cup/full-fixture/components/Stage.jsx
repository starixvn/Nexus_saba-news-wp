import React, { PureComponent } from 'react';
import CommonFunctions from '@jsroot/common/common-functions';
import DateTimeService from '@jsroot/common/dateTimeService';
import { Tabs, Tab } from 'react-bootstrap';
import { MatchStatus, Width1024 } from '@jsroot/common/constants';
import GridItem from './GridItem';
import { MobileMaxWidth } from '@jsroot/common/constants';
import MobileStage from './MobileStage';

export default class Stage extends PureComponent {
    constructor(props) {
        super(props);
        this.state = {
            selectedTab: props.league.currentStageId,
            isMobile: window.innerWidth <= MobileMaxWidth ? true : false
        };
    }


    componentDidMount() {
        window.addEventListener('resize', this.handleResize);
    }

    componentWillUnmount() {
        window.removeEventListener('resize', this.handleResize);
    }

    handleResize = () => {
        const isMobile = this.state.isMobile;
        if (!isMobile && window.innerWidth <= MobileMaxWidth) {
            this.setState({ isMobile: true });
        } else {
            if (isMobile && window.innerWidth > MobileMaxWidth) {
                this.setState({ isMobile: false });
            }
        }
    };

    groupMatchesByDate = matches => {
        const totalItems = matches.length;
        const tempMatches = [];
        for (let i = 0; i < totalItems; i++) {
            const match = matches[i];
            const startTime = DateTimeService.convertFromUTC(match.startTime);
            const key = DateTimeService.formatDateTime(startTime, 'YYYY-MM-DD');
            const item = {
                key,
                text: DateTimeService.formatDateTime(key, 'dddd, DD/MM/YYYY'),
                childs: [match]
            };
            if (tempMatches.length === 0) {
                tempMatches.push(item);
            } else {
                const index = tempMatches.findIndex(x => x.key === key);
                if (index === - 1) {
                    tempMatches.push(item);
                } else {
                    tempMatches[index].childs.push(match);
                }
            }
        }

        return tempMatches;
    };

    groupMatchesByStage = (matches, league) => {
        let totalItems = matches.length;
        const tempMatches = [];
        for (let i = 0; i < totalItems; i++) {
            const match = matches[i];
            const stage = match.stage;
            const key = stage ? stage.stageId : 0;
            const item = {
                key,
                stage,
                matches: [{...match, league}]
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

        totalItems = tempMatches.length;
        const result = [];
        for (let i = 0; i < totalItems; i++) {
            const item = tempMatches[i];
            let text;

            result.push({
                key: item.key,
                stage: item.stage,
                childs: this.groupMatchesByDate(item.matches)
            });
        }
        return result;
    };

    handleSelectTab = key => {
        this.setState({selectedTab: key});
    };


    render() {
        const { league, matches } = this.props;
        const { selectedTab, isMobile } = this.state;
        const stages = this.groupMatchesByStage(matches, league);
        console.log(1);
        return (
            <div className="livescore-soccer-main" >
                {
                    !isMobile &&
                    <Tabs className="live-score-nav" activeKey={selectedTab} onSelect={this.handleSelectTab}>
                        {
                            stages.map(group => {
                                return (
                                    <Tab eventKey={group.key} title={CommonFunctions.getStageName(group.stage)}  key={group.key}  className="season-standings">
                                        <GridItem items={group.childs}/>
                                    </Tab>
                                );
                            })
                        }
                    </Tabs>
                }
                {
                    isMobile &&
                    <MobileStage stages={stages} />
                }
            </div>
        )
    }
}