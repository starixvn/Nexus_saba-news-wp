import React, { PureComponent } from 'react';
import { getVoteResult } from '../../common.functions';
import Item from './Item';
import { WorldCup } from '@jsroot/common/constants';
import { format } from 'date-fns';

export default class Winner extends PureComponent {
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
        }
        else{
            const count = users.filter(x=>x.userId === vote.userId).length;
            if(count === 0){
                users.push(vote);
            }
        }
        return users;
    };

    handleWinners = (users, votes, points, detail) =>{
        let Winner = [];
        users.map((user,indexUser) =>{
            let pointNumber = 0;
            let createdDate = user.createdDate, 
            updatedDate = user.updatedDate;
            votes.map( (vote, index) => {
                if(user.userId === vote.userId){
                    pointNumber += points[index];
                    if(user.createdDate < vote.createdDate){
                        createdDate = vote.createdDate
                    }
                    if(user.updatedDate < vote.updatedDate){
                        updatedDate = vote.updatedDate
                    }
                }
            });
            Winner.push({
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
        Winner = this.sortWinnerPoint(Winner);
        return Winner;
    };

    getMatchesByStageId = (matches, stageId) => {
        let matchesByStageId = [];
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
        let matchesEightTeamStage = this.getMatchesByStageId(matches,WorldCup.EightTeamStage),
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
        let points = [];      
        let users = [];
        let detail = [];
        votes.map(vote => {
            let matchVote = this.getMatch(vote, matches);
            let result = getVoteResult(matchVote, vote);
            if(result != null && result.status){
                points.push(result.score);
            }
            else{
                points.push(0);
            }
            users = this.getUsersVote(users, vote);
        }); 
        detail = this.getDetail(matches, users, points, votes);    
        let Winner = this.handleWinners(users, votes, points, detail);
        if(search != ""){
           Winner = this.handleSearch(Winner, search);
        }       
        return Winner;
    };
    
    sortWinnerPoint = (Winner) =>{
        Winner = this.sortWinnerDate(Winner);
        Winner.map((item, index) =>{
            let max = item.point;
            let indexMax = index;
            Winner.map((Winner, indexSort) => {
                if(indexSort > index){
                    if(max < Winner.point){
                        max = Winner.point;
                        indexMax = indexSort;
                    }
                }
            });
            let swap = Winner[index];
            Winner[index] = Winner[indexMax];
            Winner[indexMax] = swap;
        });
        return Winner;
    };

    sortWinnerDate = (Winner) =>{
        Winner.map((item, index) =>{
            let max = item.updatedDate != null ? item.updatedDate : item.createdDate;
            let indexMax = index;
            Winner.map((Winner, indexSort) => {
                if(indexSort > index){
                    let maxSort = Winner.updatedDate != null ? Winner.updatedDate : Winner.createdDate;
                    if(max > maxSort){
                        max = maxSort;
                        indexMax = indexSort;
                    }
                }
            });
            let swap = Winner[index];
            Winner[index] = Winner[indexMax];
            Winner[indexMax] = swap;
        });
        return Winner;
    };

    checkUser = (Winners, userId) => {
        Winners.map(index => {
            if(index.userId === userId){
                return true;
            }
        });
        return false;
    } ;

    checkRank = (Winners, userId ) =>{
        let rank = 0;
        if(userId === undefined){
            rank = 0;
        }
        else{
            Winners.map((item, index) => {
                if(item.userId === userId){
                    rank = index + 1;
                }
            });
        }
        return rank
    };

    handleSearch = (Winners, search) =>{
        let searches = [];
        Winners.map(item => {
            var name = JSON.stringify(item.userName);
            if(name.toLowerCase().includes(search.toLowerCase())){
                searches.push(item);
            }
        });
        return searches;
    };

    render() {
        const { matches, votes, search } = this.props;
        const Winners = this.calculatorPointer(matches, votes, search);
        const userId = window.User;
        const rank = this.checkRank(Winners, userId);
        return (
            <>
            	<div className="minigame-table table-winner">
                    {
                        rank > 0 && <p>Thứ hạng của bạn: {rank}</p>
                    }

                    <div className="minigame-table-flex minigame-table-header">
                        <div className="table-row table-row-index">#</div>
                        <div className="table-row table-row-name">Tên</div>
                        <div className="table-row table-row-score">Điểm</div>
                        <div className="table-row table-row-time">Thời gian<i className="icon-warning"/> </div>
                    </div>
                    <div className="minigame-table-body">
                        {
                            Winners.map((item, index) => <Item key={index} number={index} Winner={item} winnnerLength={Winners.length} />)
                        }
                    </div>
                </div>
            </>
        );
    }
}
