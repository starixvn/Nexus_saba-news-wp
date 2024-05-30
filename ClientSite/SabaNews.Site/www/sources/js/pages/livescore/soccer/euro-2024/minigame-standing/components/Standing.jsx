import Item from './Item';
import { getVoteResult } from '../../common.functions';
import { WorldCup, MobileMaxWidth } from '@jsroot/common/constants';
import { Euro } from '../../euro-constants';
import dayjs from 'dayjs';
import DateTimeService from '@jsroot/common/dateTimeService';
import React, { PureComponent } from 'react';

export default class Standing extends PureComponent {
    constructor(props) {
        super(props);
        this.elementRef = React.createRef();
        this.state = {
            isMobile: window.innerWidth <= MobileMaxWidth ? true : false
        };
    }

    componentWillUnmount() {
        window.removeEventListener('resize', this.handleResize);
    }

    componentDidMount() {
        window.addEventListener('resize', this.handleResize);
        const { isMobile } = this.state;
        const numberStroll = parseInt((window.rank - 1) / 10, 10) * (isMobile? 470 : 530);
        this.elementRef.current && this.elementRef.current.scrollTo({
            top: numberStroll > 0 ? numberStroll : 0,
            behavior: 'smooth'
        });
        const tab = window.location.hash;
        if (tab === '#minigame-standing') {
            window.scrollTo({
                top: 1400,
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

    componentDidUpdate(){
        const { isMobile } = this.state;
        const numberStroll = parseInt((window.rank - 1) / 10, 10) * (isMobile? 470 : 530);
        this.elementRef.current && this.elementRef.current.scrollTo({
            top: numberStroll > 0 ? numberStroll : 0,
            behavior: 'smooth'
        });
    }

    calculatorPointerDetail = (points, votes, matches, userId) => {
        let pointWinner = 0;
        votes.map((vote, index) => {
            matches.map(match => {
                if(userId === vote.userId && match.matchId === vote.matchId){
                    pointWinner += points[index];
                }
            });
        });
        return pointWinner;
    };

    getMatchesByStageId = (matches, stageId) =>  matches.filter(m => m.stage.stageId === stageId);

    getDetail = (matches, users, points, votes) => {
        const matchesEightTeamStage = this.getMatchesByStageId(matches, Euro.EightTeamStage),
        matchesQuarterfinalsStage = this.getMatchesByStageId(matches, Euro.QuarterfinalsStage),
        matchesFinalStage = this.getMatchesByStageId(matches, Euro.FinalStage),
        matchesSemifinalsStage = this.getMatchesByStageId(matches,Euro.SemifinalsStage),
        detail = [];
        users.map(user => {
            detail.push({
                pointEightTeamStage: this.calculatorPointerDetail(points, votes, matchesEightTeamStage, user.userId),
                pointSemifinalsStage: this.calculatorPointerDetail(points, votes, matchesSemifinalsStage, user.userId),
                pointQuarterfinalsStage: this.calculatorPointerDetail(points, votes, matchesQuarterfinalsStage, user.userId),
                pointFinalStage: this.calculatorPointerDetail(points, votes, matchesFinalStage, user.userId)
            });
        });
        return detail;
    };

    calculatorPointer = (matches, votes) => {
        let detail = [];
        const points = [];
        let users = [];
        votes.map(vote => {
            let {createdDate, updatedDate} = vote;
            var key = vote.userId;
            var point = 0;
            const matchVote = matches.find(match => match.matchId === vote.matchId);
            const result = getVoteResult(matchVote, vote);
            point = result != null && result.status ? result.score : point;
            points.push(point);
            const user = {
                id: vote.userId,
                icon: vote.icon,
                userName: vote.userName,
                point: point,
                createdDate:  DateTimeService.convertFromUTC(createdDate),
                updatedDate:  DateTimeService.convertFromUTC(updatedDate)
            }
            if(users.length === 0) {
                users.push(user);
            } else {
                const userIndex =  users.findIndex(x => x.userId === key);
                if(userIndex === -1) {
                    users.push(user)
                } else {
                    if(updatedDate > users[userIndex].updatedDate){
                        users[userIndex].updatedDate = updatedDate;
                    }
                    if(createdDate > users[userIndex].createdDate){
                        users[userIndex].createdDate = createdDate;
                    }
                    users[userIndex].point += point;
                }
            }
        });
        const standing = this.sortStandingPoint([...users]);
        return standing;
    };

    sortStandingPoint = standing =>{
        return standing.sort((a, b) => (b.point - a.point) || dayjs(a.updatedDate).diff(dayjs(b.updatedDate)))
    };

    checkRank = (standings, userId ) =>{
        const userIndex = standings.findIndex(s => s.userId = userId);
        const rank = userIndex >= 0 ? userIndex + 1 : 0
        return rank;
    };

    render() {
        const { matches, votes, stagesSelected } = this.props;
        const standings = this.calculatorPointer(matches, votes);
        const userId = window.User;
        const rank = this.checkRank(standings, userId);
        window.rank = rank;
        return (
            <>
                {
                    rank > 0 && <p className="standing-me">Thứ hạng của bạn: {rank}</p>
                }
                <div className="wrapper-scroll">
                    <div className="minigame-table">
                        <div className="minigame-table-flex minigame-table-header">
                            <div className="table-row table-row-index">#</div>
                            <div className="table-row table-row-name">Tên</div>
                            <div className="table-row table-row-score">Điểm</div>
                            <div className="table-row table-row-time">Thời gian </div>
                        </div>
                        <div ref={this.elementRef} className= {`minigame-table-body ${standings.length === 0 ? 'no-data' : ''}`}>
                            {
                                standings.map((item, index) => <Item rank={rank} key={index} number={index} standing={item}
                                                                    standingLength={standings.length} stagesSelected={stagesSelected} />)
                            }
                            {
                                standings.length === 0 && <p className="text-no-data">Dữ Liệu Đang Được Cập Nhật</p>
                            }
                        </div>
                    </div>
                </div>
            </>
        );
    }
}
