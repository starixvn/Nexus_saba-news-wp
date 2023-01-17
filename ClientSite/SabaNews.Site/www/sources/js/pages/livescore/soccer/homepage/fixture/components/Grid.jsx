import React, { PureComponent } from 'react';
import GridItem from './GridItem';
import { MatchStatus } from '@jsroot/common/constants';
import CommonFunctions from '@jsroot/common/common-functions';
import { Tabs, Tab } from 'react-bootstrap';
import moment from 'moment';
import DateTimeService from '@jsroot/common/dateTimeService';
import Site from '@jsroot/common/site';

export default class Grid extends PureComponent {
    constructor(props) {
        super(props);
        this.state = {
            selectedTab: 'fixture'
        }
    }


    handleSelectTab = key => {
        this.setState({ selectedTab: key });
    };

    sortMatches = (matches, sortAsc) => {
        if (sortAsc) {
            return matches.sort((item1, item2) => moment(item1.startTime).diff(moment(item2.startTime)));
        } else {
            return matches.sort((item1, item2) => moment(item2.startTime).diff(moment(item1.startTime)));
        }
    };

    filterPreMatches = matches => {
        const preMatches = [];
        const today = moment(DateTimeService.getCurrentClientDateTime()).format('YYYY-MM-DD');
        for (let i = 0; i < matches.length; i++) {
            const match = matches[i];
            const endTime = moment(DateTimeService.convertFromUTC(match.endTime)).format('YYYY-MM-DD');
            if (match.status !== MatchStatus.Finished && moment(endTime).isSameOrAfter(today)) {
                preMatches.push(match);
            }
        }
        return preMatches;
    };

    handleClickLeague = (event, league) => {
        const url = CommonFunctions.getLeaguePageUrl(league);
        if (event.ctrlKey) {
            window.open(url, '_blank');
        } else {
            window.location = url;
        }
    };
    render() {
        const { league, matches } = this.props;
        if(matches.length === 0) {
            return null;
        }
        let postMatches = matches.filter(item => item.status === MatchStatus.Finished);
        let preMatches = this.filterPreMatches(matches);
        postMatches = this.sortMatches(postMatches, false);
        return (
            <div className="wc-bg-overflow">
                <div className="livescore-name-team">
                    <span className="round-name"  onClick={event => this.handleClickLeague(event, league)}>{CommonFunctions.getLeagueName(league)}</span>
                </div>
                <Tabs  id="livescore-tab-league-detail"
                    className="live-score-nav"
                >
                    <Tab title="LTĐ" eventKey="fixture">
                        <GridItem matches={preMatches} league={league} isFixture />
                    </Tab>
                    <Tab title="KQ" eventKey="result">
                        <GridItem matches={postMatches} league={league} />
                    </Tab>
                </Tabs>
            </div>
        );
    }
}