import Item from './Item';
import { format } from 'date-fns';
import { getVoteResult } from '../../common.functions';
import { WorldCup } from '@jsroot/common/constants';
import React, { PureComponent } from 'react';

export default class Standing extends PureComponent {
    constructor(props) {
        super(props);
    }

    getMatch = (vote, matches) =>{
        const matchVote = matches.filter(match => match.matchId === vote.matchId)[0];
        return !matchVote ? null : matchVote;
    };

    getUsersVote = (users, vote) =>{
        if(users.length === 0){
            users.push(vote);
        }else{
            const count = users.filter(x=>x.userId === vote.userId).length;
            if(count === 0){
                users.push(vote);
            }
        }
        return users;
    };

    handleStandings = (users, votes, points, detail) =>{
        let standing = [];
        users.map((user,indexUser) =>{
            let pointNumber = 0;
            let {createdDate, updatedDate} = user;
            votes.map( (vote, index) => {
                if(user.userId === vote.userId){
                    pointNumber += points[index];
                    if(createdDate < vote.createdDate){
                        createdDate = vote.createdDate
                    };
                    if(updatedDate < vote.updatedDate){
                        updatedDate = vote.updatedDate
                    };
                }
            });
            standing.push({
                userId: user.userId,
                matchId : user.matchId,
                teamId : user.teamId,
                stage : user.stage,
                createdDate : format(new Date(createdDate), 'dd/MM/yyyy, HH:mm:ss'),
                updatedDate : format(new Date(updatedDate), 'dd/MM/yyyy, HH:mm:ss'), 
                userName : user.userName,
                icon : user.icon,
                point : pointNumber,
                detail: detail[indexUser]
            });
        }) ;
        standing = this.sortStandingPoint(standing);
        return standing;
    };

    getMatchesByStageId = (matches, stageId) => {
        const matchesByStageId = [];
        matches.map(match => {
            if(match.stage.stageId === stageId){
                matchesByStageId.push(match);
            }
        });
        return matchesByStageId;
    };

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

    calculatorPointer = (matches, votes, search) => {
        const points = [];
        let users = [];
        let detail = [];
        votes.map(vote => {
            const matchVote = this.getMatch(vote, matches);
            const result = getVoteResult(matchVote, vote);
            if(result != null && result.status){
                points.push(result.score);
            }else{
                points.push(0);
            }
            users = this.getUsersVote(users, vote);
        });
        detail = this.getDetail(matches, users, points, votes);
        let standing = this.handleStandings(users, votes, points, detail);
        if(search !== ''){
           standing = this.handleSearch(standing, search);
        }
        return standing;
    };

    sortStandingPoint = standing =>{
        standing = this.sortStandingDate(standing);
        standing.map((item, index) =>{
            let max = item.point;
            let indexMax = index;
            standing.map((standing, indexSort) => {
                if(indexSort > index && max < standing.point){
                        max = standing.point;
                        indexMax = indexSort;
                }
            });
            const swap = standing[index];
            standing[index] = standing[indexMax];
            standing[indexMax] = swap;
        });
        return standing;
    };

    sortStandingDate = standing =>{
        standing.map((item, index) =>{
            let max = item.updatedDate != null ? item.updatedDate : item.createdDate;
            let indexMax = index;
            standing.map((standing, indexSort) => {
                if(indexSort > index){
                    const maxSort = standing.updatedDate != null ? standing.updatedDate : standing.createdDate;
                    if(max > maxSort){
                        max = maxSort;
                        indexMax = indexSort;
                    }
                }
            });
            const swap = standing[index];
            standing[index] = standing[indexMax];
            standing[indexMax] = swap;
        });
        return standing;
    };

    checkUser = (standings, userId) => {
        standings.map(index => {
            if(index.userId === userId){
                return true;
            }
        });
        return false;
    } ;

    checkRank = (standings, userId ) =>{
        let rank = 0;
        if(userId === undefined){
            rank = 0;
        }else{
            standings.map((item, index) => {
                if(item.userId === parseInt(userId, 10)){
                    rank = index + 1;
                }
            });
        }
        return rank;
    };

    handleSearch = (standings, search) =>{
        const searches = [];
        standings.map(item => {
            const name = JSON.stringify(item.userName);
            if(name.toLowerCase().includes(search.toLowerCase())){
                searches.push(item);
            }
        });
        return searches;
    };

    render() {
        const { matches, votes, search } = this.props;
        const standings = this.calculatorPointer(matches, votes, search);
        const userId = window.User;
        const rank = this.checkRank(standings, userId);
        return (
            <>
                {
                    rank > 0 && <p className="standing-me">Thứ hạng của bạn: {rank}</p>
                }
            	<div className="minigame-table">
                    <div className="minigame-table-flex minigame-table-header">
                        <div className="table-row table-row-index">#</div>
                        <div className="table-row table-row-name">Tên</div>
                        <div className="table-row table-row-score">Điểm</div>
                        <div className="table-row table-row-time">Thời gian <i className="icon-warning"/> </div>
                    </div>
                    <div className="minigame-table-body">
                        {
                            standings.map((item, index) => <Item key={index} number={index} standing={item} standingLength={standings.length} />)
                        }
                    </div>
                </div>
            </>
        );
    }
}
