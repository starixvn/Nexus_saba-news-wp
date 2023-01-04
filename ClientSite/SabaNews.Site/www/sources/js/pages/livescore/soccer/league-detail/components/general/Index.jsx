import React, { PureComponent } from 'react';
import { Form } from 'react-bootstrap';
import moment from 'moment';
import DateTimeService from '@jsroot/common/dateTimeService';
import CommonFunctions from '@jsroot/common/common-functions';
import LangHelper from '@jsroot/common/langHelper';
import { LeagueType } from '@jsroot/common/constants';

export default class General extends PureComponent {
    constructor(props) {
        super(props);

        this.state = {
            searchKey: ''
        };
    }

    checkGroupLeagueType =  (stages, matches, league) => {
        return stages.length <= 1
            && matches.filter(item => item.round > 0).length === 0
            && league.leagueType === LeagueType.League;
    };

    checkGroupMatches = (stages, matches, league) => {
        return this.checkGroupLeagueType(stages, matches, league)
        || (stages.length === 0 && matches.filter(item => item.round > 0).length === 0)
        || league.leagueType === LeagueType.Friendly;
    };

    groupMatchesByStageAndRound = matches => {
        const totalItems = matches.length;
        const tempMatches = [];
        for (let i = 0; i < totalItems; i++) {
            const match = matches[i];
            const stage = match.stage;
            const stageId = stage ? stage.stageId : 0;
            const round = match.round ? match.round : 0;
            const item = {
                stageId, stage, round,
                matches: [match]
            };

            if (tempMatches.length === 0) {
                tempMatches.push(item);
            } else {
                const lastItem = tempMatches[tempMatches.length - 1];
                if (stageId === lastItem.stageId && round === lastItem.round) {
                    tempMatches[tempMatches.length - 1].matches.push(match);
                } else {
                    tempMatches.push(item);
                }
            }
        }

        return tempMatches;
    };

    groupMatches = (stages, matches, league) => {
        if (this.checkGroupMatches(stages, matches, league)) {
            return CommonFunctions.groupMatchesByDate(matches);
        }

        const tempMatches = this.groupMatchesByStageAndRound(matches);

        const totalItems = tempMatches.length;
        const result = [];
        for (let i = 0; i < totalItems; i++) {
            const item = tempMatches[i];
            let text;
            if(!item.stage) {
                text = '';
            } else if (item.round > 0 && item.stage.roundCount > 1 && league.leagueType === LeagueType.Cup) {
                text = `${CommonFunctions.getStageName(item.stage)} - ${this.getRoundName(item.round, league.leagueType)}`;
            } else if (item.round > 0 && item.stage.roundCount > 1 && league.leagueType !== 2) {
                text = this.getRoundName(item.round, league.leagueType);
            } else {
                text = CommonFunctions.getStageName(item.stage);
            }

            result.push({
                key: i,
                text,
                childs: CommonFunctions.groupMatchesByDate(item.matches)
            });
        }

        return result;
    };

    sortMatches = (matches, sortAsc) => {
        if (sortAsc) {
            return matches.sort((item1, item2) => moment(item1.startTime).diff(moment(item2.startTime)));
        } else {
            return matches.sort((item1, item2) => moment(item2.startTime).diff(moment(item1.startTime)));
        }
    };

    filterMatches = (matches, searchKey) => {
        return matches.filter(match =>
            CommonFunctions.getTeamName(match.homeTeam).toLowerCase().indexOf(searchKey.toLowerCase()) !== -1
            || CommonFunctions.getTeamName(match.awayTeam).toLowerCase().indexOf(searchKey.toLowerCase()) !== -1);
    };

    filterPreMatches = matches => {

    };

    filterTodayMatches = matches => {
        const resultMatches = [];
        const today = moment(DateTimeService.getCurrentClientDateTime()).format('YYYY-MM-DD');
        matches.forEach(item => {
            const startTime = moment(DateTimeService.convertFromUTC(item.startTime)).format('YYYY-MM-DD');
            if(moment(startTime).isSame(today)) {
                resultMatches.push(item);
            }
        });
        return resultMatches;
    };

    render() {
        let { league, stages, preMatches, postMatches } = this.props;
        if (preMatches.length === 0 && postMatches.length === 0) {
            return <div className="none-livescore">Đang cập nhật dữ liệu...</div>;
        }
        const todayMatches = this.filterTodayMatches(preMatches);
        console.log(todayMatches);

        return (
            <div className="league-story">
                123
            </div>
        );
    }
}