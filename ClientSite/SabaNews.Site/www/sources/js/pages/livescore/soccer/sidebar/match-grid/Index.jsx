import React, { PureComponent } from 'react';
import Group from './Group';
import { MatchStatus } from '@jsroot/common/constants';
import CommonFunctions from '@jsroot/common/common-functions';
import DateTimeService from '@jsroot/common/dateTimeService';
import dayjs from 'dayjs';
import isSameOrAfter from 'dayjs/plugin/isSameOrAfter';

dayjs.extend(isSameOrAfter);

export default class MatchGrid extends PureComponent {
    constructor(props) {
        super(props);
        this.state = {
            selectedTab: 'fixture'
        }
    }


    handleSelectedTab = key => {
        this.setState({ selectedTab: key });
    };

    sortMatches = (matches, sortAsc) => {
        if (sortAsc) {
            return matches.sort((item1, item2) => dayjs(item1.startTime).diff(dayjs(item2.startTime)));
        } else {
            return matches.sort((item1, item2) => dayjs(item2.startTime).diff(dayjs(item1.startTime)));
        }
    };

    filterPreMatches = matches => {
        const preMatches = [];
        const today = dayjs(DateTimeService.getCurrentClientDateTime()).format('YYYY-MM-DD');
        for (let i = 0; i < matches.length; i++) {
            const match = matches[i];
            const endTime = dayjs(DateTimeService.convertFromUTC(match.endTime)).format('YYYY-MM-DD');
            if (match.status !== MatchStatus.Finished && match.status !== MatchStatus.CutInHalf && dayjs(endTime).isSameOrAfter(today)) {
                preMatches.push(match);
            }
        }
        return preMatches;
    };

    handleClickLeague = (event, league) => {
        const seasonId = league.season ? league.season.seasonId : 0;
        const url = CommonFunctions.getLeaguePageUrl(league, seasonId);
        if (event.ctrlKey) {
            window.open(url, '_blank');
        } else {
            window.location = url;
        }
    };

    getCurrentMatches = () => {
        const {selectedTab} = this.state;
        const { matches } = this.props;
        let matchList = [];
        if(selectedTab === 'fixture') {
            matchList =  this.filterPreMatches(matches);
        } else if(selectedTab === 'result') {
            matchList = matches.filter(item => item.status === MatchStatus.Finished || item.status === MatchStatus.CutInHalf);
            matchList = this.sortMatches(matchList, false);
        }
        return matchList;
    };
    render() {
        const { league, matches, isShowEmptyFixture } = this.props;
        const { selectedTab } = this.state;
        if (matches.length === 0) {
            return null;
        }
        const matchList = this.getCurrentMatches();
        if (matchList.length === 0 && !isShowEmptyFixture) {
            return null;
        }
        const isFixture = selectedTab === 'fixture';
        return (
            <div className="wc-bg-overflow">
                <div className="livescore-name-team">
                    <span className="round-name" onClick={event => this.handleClickLeague(event, league)}>{CommonFunctions.getLeagueName(league)}</span>
                </div>
                <ul className="live-score-nav">
                    <li className={selectedTab === 'fixture' ? 'active' : ''} onClick={() => this.handleSelectedTab('fixture')}>LTĐ</li>
                    <li className={selectedTab === 'result' ? 'active' : ''} onClick={() => this.handleSelectedTab('result')}>KQ</li>
                </ul>
                <Group matches={matchList} league={league} isFixture={isFixture} />
            </div>
        );
    }
}