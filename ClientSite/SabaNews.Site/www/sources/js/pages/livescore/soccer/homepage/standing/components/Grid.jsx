import React, { PureComponent } from 'react';
import CommonFunctions from '@jsroot/common/common-functions';
import GridItem from './GridItem';
export  default class Grid extends PureComponent {
    constructor(props) {
        super(props);

        this.state = {
            selectedLeagueId: this.initSelectedLeagueId()
        };
    }

    initSelectedLeagueId = () => {
        const { standings } = this.props;
        let selectedId = 0;
        for(let i = 0; i < standings.length; i++) {
            const item = standings[i];
            if(item.standing.length > 0) {
                selectedId = item.league.leagueId;
                break;
            }
        }
        return selectedId;
    }
    onChange = event => {
        this.setState({selectedLeagueId: parseInt(event.target.value)});
    };

    getLeagueStanding = () => {
        const { selectedLeagueId } = this.state;
        const {standings} = this.props;
        const currentStanding = standings.find(item => item.league.leagueId === selectedLeagueId);
        return currentStanding;
    }
    render() {
        const { standings } = this.props;
        const { selectedLeagueId } = this.state;
        const currentLeagueStanding = this.getLeagueStanding();
        return (
            <div className="season-standings">
                <select  onChange={this.onChange} value={selectedLeagueId}>
                    {
                        standings.map((standing, index) =>
                        standing.standing.length > 0 &&
                        <option value={standing.league.leagueId} key={index} >{CommonFunctions.getLeagueName(standing.league)}</option>)
                    }
                </select>
                <div>
                    <GridItem leagueStanding={currentLeagueStanding} />
                </div>
            </div>
        )
    }
}