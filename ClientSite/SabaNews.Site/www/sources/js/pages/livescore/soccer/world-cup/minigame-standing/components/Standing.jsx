import React, { PureComponent } from 'react';
import { getVoteResult } from '../../common.functions';
import Item from './Item';
import { WorldCup } from '@jsroot/common/constants';

export default class Standing extends PureComponent {
    constructor(props) {
        super(props);
    }

    getMatch = (vote, matches) =>{
        let matchVote = null;
        matches.map(match => {
            if(match.matchId === vote.matchId){
                matchVote = match;
            }
        });
        return matchVote;
    };

    getUsersVote = (users, vote) =>{
        if(users.length === 0){
            users.push(vote);
        }
        else{
            let dem = 0;
            users.map(user => {
                if(user.userId === vote.userId){
                    dem++;
                }
            });
            if(dem === 0){
                users.push(vote);
            }
        }
        return users;
    };

    handleStandings = (users, votes, points, detail) =>{
        let standing = [];
        users.map((user,indexUser) =>{
            let pointNumber = 0;
            votes.map( (vote, index) => {
                if(user.userId === vote.userId){
                    pointNumber += points[index];
                }
            });
            standing.push({
                userId: user.userId,
                matchId : user.matchId,
                teamId : user.teamId,
                stage : user.stage,
                createdDate : user.createdDate,
                updatedDate : user.updatedDate,
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
        let standing = this.handleStandings(users, votes, points, detail);
        if(search != ""){
           standing = this.handleSearch(standing, search);
        }       
        return standing;
    };
    
    sortStandingPoint = (standing) =>{
        standing = this.sortStandingDate(standing);
        standing.map((item, index) =>{
            let max = item.createdDate;
            let indexMax = index;
            standing.map((standing, indexSort) => {
                if(indexSort > index){
                    if(max < standing.point){
                        max = standing.point;
                        indexMax = indexSort;
                    }
                }
            });
            let swap = standing[index];
            standing[index] = standing[indexMax];
            standing[indexMax] = swap;
        });
        return standing;
    };

    sortStandingDate = (standing) =>{
        standing.map((item, index) =>{
            let max = item.updatedDate != null ? item.updatedDate : item.createdDate;
            let indexMax = index;
            standing.map((standing, indexSort) => {
                if(indexSort > index){
                    let maxSort = standing.updatedDate != null ? standing.updatedDate : standing.createdDate;
                    if(max > maxSort){
                        max = maxSort;
                        indexMax = indexSort;
                    }
                }
            });
            let swap = standing[index];
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
        }
        else{
            standings.map((item, index) => {
                if(item.userId === userId){
                    rank = index + 1;
                }
            });
        }
        return rank
    };

    handleSearch = (standings, search) =>{
        let searches = [];
        standings.map(item => {
            var name = JSON.stringify(item.userName);
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
            	<div className="minigame-table">
                {
                    rank > 0 && <p>Thứ hạng của bạn: {rank}</p>
                }
                    <div className="soccer-match-grid-item-body">
                            <div style={{display:'flex', justifyContent:'space-between'}}>
                                <div>#</div>
                                <div>Tên</div>
                                <div>Điểm</div>
                                <div>Thời gian</div>
                                <div></div>
                            </div>
                        {
                            standings.map((item, index) => <Item key={index} number={index} standing={item} />)
                        }
                    </div>
                </div>
            </>
            
        );
    }
}
