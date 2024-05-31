import React, { PureComponent } from 'react';
import { getVoteResult } from '../../common.functions';
import Item from './Item';
import { Euro } from '../../euro-constants';
import DateTimeService from '@jsroot/common/dateTimeService';
import dayjs from 'dayjs';

export default class Winner extends PureComponent {
    constructor(props) {
        super(props);
    }

    componentDidMount() {
        const tab = window.location.hash;
        if (tab === '#minigame-winners') {
            window.scrollTo({
                top: $(tab).offset().top,
                behavior: 'smooth'
            });
        }
        if (tab === '#stage-minigame') {
            window.scrollTo({
                top: 600,
                behavior: 'smooth'
            });
        }
    }

    getMatchesByStageId = (matches, stageId) =>  matches.filter(m => m.stage.stageId === stageId);


    calculatorPointerDetail = (points, votes, matches, userId) => {
        let point = 0;
        votes.map((vote, index) => {
            matches.map(match => {
                if(userId === vote.userId && match.matchId === vote.matchId){
                    point += points[index];
                }
            });
        });
        return point;
    };

    getDetail = (matches, users, points, votes) => {
        const matchesEightTeamStage = this.getMatchesByStageId(matches,WorldCup.EightTeamStage),
        matchesQuarterfinalsStage = this.getMatchesByStageId(matches,WorldCup.QuarterfinalsStage),
        matchesSemifinalsStage = this.getMatchesByStageId(matches,WorldCup.SemifinalsStage),
        matchesThirdPlace = this.getMatchesByStageId(matches,WorldCup.ThirdPlace),
        matchesFinalStage = this.getMatchesByStageId(matches,WorldCup.FinalStage),
        detail = [];
        users.map(user => {
            detail.push({
                pointEightTeamStage: this.calculatorPointerDetail(points, votes, matchesEightTeamStage, user.userId),
                pointQuarterfinalsStage: this.calculatorPointerDetail(points, votes, matchesQuarterfinalsStage, user.userId),
                pointSemifinalsStage: this.calculatorPointerDetail(points, votes, matchesSemifinalsStage, user.userId),
                pointThirdPlace: this.calculatorPointerDetail(points, votes, matchesThirdPlace, user.userId),
                pointFinalStage: this.calculatorPointerDetail(points, votes, matchesFinalStage, user.userId)
            });
        });
        return detail;
    };

    isKnockoutMatch = match => {
        return match.stage.stageId !== Euro.GroupStage;
    };

    calculatorPointer = (matches, votes) => {
        const points = [];
        let users = [];
        votes.map(vote => {
            const updatedDate = DateTimeService.convertFromUTC(vote.updatedDate)
            const matchVote = matches.find(match => match.matchId === vote.matchId);

            if (!matchVote) return;

            const result = getVoteResult(matchVote, vote);
            const point = result != null && result.status ? result.score : 0;
            points.push(point);

            const userIndex =  users.findIndex(x => x.id === vote.userId);
            var knockout = { stage: matchVote.stage.stageId, point: point };
            var detail = this.isKnockoutMatch(matchVote) ? knockout : null;
            if (userIndex === -1) {
                const details = detail != null ? [detail] : [];
                users.push({
                    id: vote.userId,
                    icon: vote.icon,
                    userName: vote.userName,
                    point: point,
                    updatedDate: updatedDate,
                    details
                });
            } else {
                const user = users[userIndex];
                user.point += point;
                if (user.updatedDate < updatedDate) {
                    user.updatedDate = updatedDate;
                }
                if(detail !== null) {
                    user.details.push(knockout);
                }
            }
        });
        const standing = this.sortWinnerPoint([...users]);
        return standing;
    };

    sortWinnerPoint = Winner =>{
        return Winner.sort((a, b) => (b.point - a.point) || dayjs(a.updatedDate).diff(dayjs(b.updatedDate)))
    };

    render() {
        const { matches, votes, selectedStage} = this.props;
        const winners = this.calculatorPointer(matches, votes);
        return (
            <div className="wrapper-scroll wrapper-scroll-winner">
                <div className="minigame-table table-winner">
                    <div className="minigame-table-flex minigame-table-header">
                        <div className="table-row table-row-index">#</div>
                        <div className="table-row table-row-name">Tên</div>
                        <div className="table-row table-row-score">Điểm</div>
                        <div className="table-row table-row-time">Thời gian</div>
                    </div>
                    <div className="minigame-table-body">
                        {
                            winners.map((item, index) => <Item key={index} number={index} Winner={item} winnnerLength={winners.length} selectedStage={selectedStage} />)
                        }
                    </div>
                </div>
            </div>
        );
    }
}
