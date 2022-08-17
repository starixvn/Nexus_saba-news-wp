import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import DateTimeService from '@jsroot/common/dateTimeService';
import { StageMode } from '@jsroot/common/constants';
import Grid from './Grid';

export class AppContainer extends Component {
    constructor(props) {
        super(props);
    }

    componentDidMount() {
        this.props.getMatches();
    }

    getPlayoffStages = () => {
        const { stages } = this.props;
        const pointsStage = stages.filter(item => item.mode === StageMode.Points);
        const maxOrder = Math.max(...pointsStage.map(item => item.orderIndex));
        let playoffStages = stages.filter(item =>
            item.mode === StageMode.Knockout
            && !item.stageNameEN.toLowerCase().includes('place')
            && item.orderIndex > maxOrder
        );
        return playoffStages;
    };

    groupMatchByStage = () => {
        const { matches, league } = this.props;
        const playoffStages = this.getPlayoffStages();
        let tempMatches = [];
        matches.map(value => {
            const match = {...value, league};
            const stage = match.stage;
            const stageIndex = playoffStages.findIndex(item => item.stageId === stage.stageId);
            if(stageIndex < 0) {
                return;
            }
            const key = stage.stageId;
            const item = {
                key,
                stage,
                matches: [match]
            };

            if (tempMatches.length === 0) {
                tempMatches.push(item);
            } else {
                const index = tempMatches.findIndex(x => x.key === key);
                if (index === - 1) {
                    tempMatches.push(item);
                } else {
                    tempMatches[index].matches.push(match);
                }
            }
        });
        tempMatches = tempMatches.sort((item1, item2) => item2.stage.orderIndex - item1.stage.orderIndex);
        return tempMatches;
    };

    sortStage = stages => stages.sort((item1, item2) => item2.stage.orderIndex - item1.stage.orderIndex);


    groupMatches = matches => {
        const playoffStages = this.groupMatchByStage(matches);
        const totalItems = playoffStages.length;
        const tmpStages = [];
        for(let i = 0; i < totalItems; i++) {
            const stage = playoffStages[i];
            if(tmpStages.length === 0) {
                const item = {
                    ...stage,
                    matches: this.groupMatchBySecondLeg(stage.matches, false)
                };
                tmpStages.push(item);
            } else {
                const lastStage = tmpStages[tmpStages.length - 1].matches.filter(item => item.isDraw !== false);
                const matches = stage.matches;
                const matchStage = this.sortMatchByLastStage(matches, lastStage);
                const item = {
                    ...stage,
                    matches: matchStage
                };
                tmpStages.push(item);
            }
        }
        return tmpStages;
    };

    sortMatchByLastStage = (matches, lastStage) => {
        const tmpMatches = [];
        const matchIds = [];
        lastStage.map(match => {
            if(!match.matchId) {
                // const emptyMatches = this.getEmptyMatch(match, matches);
                // emptyMatches.matches.forEach(item => tmpMatches.push(item));
                // emptyMatches.matchIds.forEach(item => matchIds.push(item));
                return;
            }
            const homeMatch = matches.filter(item => item.homeTeam.teamId === match.homeTeam.teamId || item.awayTeam.teamId === match.homeTeam.teamId);
            const awayMatch = matches.filter(item => item.homeTeam.teamId === match.awayTeam.teamId || item.awayTeam.teamId === match.awayTeam.teamId);
            const homeResult = this.getMatchMapWithStage(homeMatch, match, true);
            if(homeResult.match !== null) {
                tmpMatches.push(homeResult.match);
            }
            homeResult.matchIds.forEach(item => matchIds.push(item));

            const awayResult = this.getMatchMapWithStage(awayMatch, match, false);
            if(awayResult.match !== null) {
                tmpMatches.push(awayResult.match);
            }
            awayResult.matchIds.forEach(item => matchIds.push(item));
        });
        const matchIdsFilter = matchIds.join(',');
        const outMatches = matches.filter(item => !matchIdsFilter.includes(item.matchId.toString()));
        const oMatches = this.groupMatchBySecondLeg(outMatches, true);
        return [...tmpMatches, ...oMatches];
    };

    groupMatchBySecondLeg = (matches, isOutMatches) => {
        const tmpMatches = [];
        matches.map(match => {
            if(!match.isSecondLeg) {
                const matchSecondLeg = matches.find(item => item.isSecondLeg && (item.homeTeam.teamId === match.homeTeam.teamId || item.awayTeam.teamId === match.homeTeam.teamId));
                const item = {...match};
                if(isOutMatches) {
                    item.isDraw = false;
                }
                if(matchSecondLeg) {
                    item.secondLeg = matchSecondLeg;
                }
                tmpMatches.push(item);
            }
        });
        return tmpMatches;
    };


    getEmptyMatch = (match, matchesFilter) => {
        const tmpMatches = [];
        const matchIds = [];
        if(match.awayTeam) {
            tmpMatches.push({});
            const awayMathch = matchesFilter.filter(item => item.homeTeam.teamId === match.awayTeam.teamId || item.awayTeam.teamId === match.awayTeam.teamId);
            const awayResult = this.getMatchMapWithStage(awayMathch, match, false);
            if(awayResult.match !== null) {
                tmpMatches.push(awayResult.match);
            }
            awayResult.matchIds.forEach(item => matchIds.push(item));
        }
        if(match.homeTeam) {
            const homeMatch = matchesFilter.filter(item => item.homeTeam.teamId === match.homeTeam.teamId || item.awayTeam.teamId === match.homeTeam.teamId);
            const homeResult = this.getMatchMapWithStage(homeMatch, match, true);
            if(homeResult.match !== null) {
                tmpMatches.push(homeResult.match);
            }
            homeResult.matchIds.forEach(item => matchIds.push(item));
            tmpMatches.push({});
        }
        if(!match.homeTeam &&  !match.awayTeam) {
            tmpMatches.push({});
            tmpMatches.push({});
        }
        return {matches: tmpMatches, matchIds: matchIds};
    };

    getMatchMapWithStage = (matchFilter, match, isHome) => {
        let tmpMatch = null;
        const matchIds = [];
        if(matchFilter.length === 1) {
            tmpMatch = matchFilter[0];
            matchIds.push(matchFilter[0].matchId);
        } else if(matchFilter.length === 2) {
            const firstlegIndex = matchFilter.findIndex(item => !item.isSecondLeg);
            const firstleg = {...matchFilter[firstlegIndex]};
            firstleg.secondLeg = matchFilter[1 - firstlegIndex];
            tmpMatch = firstleg;
            matchIds.push(matchFilter[0].matchId);
            matchIds.push(matchFilter[1].matchId);
        } else if(matchFilter.length === 0) {
            // if(isHome) {
            //     tmpMatch = {
            //         homeTeam: match.homeTeam
            //     };
            // } else {
            //     tmpMatch = {
            //         awayTeam: match.awayTeam
            //     };
            // }
        }
        return {match: tmpMatch, matchIds: matchIds};
    };

    groupMatchBySecondLeg = (matches, isOutMatches) => {
        const tmpMatches = [];
        matches.map(match => {
            if(!match.isSecondLeg) {
                const matchSecondLeg = matches.find(item => item.isSecondLeg && (item.homeTeam.teamId === match.homeTeam.teamId || item.awayTeam.teamId === match.homeTeam.teamId));
                const item = {...match};
                if(isOutMatches) {
                    item.isDraw = false;
                }
                if(matchSecondLeg) {
                    item.secondLeg = matchSecondLeg;
                }
                tmpMatches.push(item);
            }
        });
        return tmpMatches;
    };

    render() {
        const { matches } = this.props;
        if(!matches || matches.length === 0) {
            return null;
        }
        let groupMatches = this.groupMatches(matches);
        groupMatches = groupMatches.reverse();
        return (
            <div className="play-offs">
                <div className="draw-container">
                    <div className="draw-wrapper draw-show-pc">
                        <div className="shadowHeader"></div>
                        <div className="learHeader"></div>
                        <Grid matches={groupMatches} />
                    </div>
                </div>
            </div>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { matches, league, stages } = state;
    return { matches, league, stages };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);