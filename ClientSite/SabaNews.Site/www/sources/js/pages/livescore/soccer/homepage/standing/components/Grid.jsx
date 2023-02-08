import React, { PureComponent } from 'react';
import CommonFunctions from '@jsroot/common/common-functions';
import GridItem from './GridItem';
export default class Grid extends PureComponent {
    constructor(props) {
        super(props);

        this.state = {
            selectedLeagueId: this.initSelectedLeagueId()
        };
    }

    initSelectedLeagueId = () => {
        const { standings } = this.props;
        let selectedId = 0;
        for (let i = 0; i < standings.length; i++) {
            const item = standings[i];
            if (item.standing.length > 0) {
                selectedId = item.league.leagueId;
                break;
            }
        }
        return selectedId;
    };
    onChange = event => {
        this.setState({ selectedLeagueId: parseInt(event.target.value) });
    };

    getLeagueStanding = () => {
        const { selectedLeagueId } = this.state;
        const { standings } = this.props;
        const currentStanding = standings.find(item => item.league.leagueId === selectedLeagueId);
        return currentStanding;
    };

    handleClickStanding = (event, league) => {
        const url = `${CommonFunctions.getLeaguePageUrl(league)}/standings`;
        if (event.ctrlKey) {
            window.open(url, '_blank');
        } else {
            window.location = url;
        }
    };
    render() {
        const { standings } = this.props;
        const { selectedLeagueId } = this.state;
        const currentLeagueStanding = this.getLeagueStanding();
        if (!currentLeagueStanding) {
            return null;
        }
        return (
            <div>
                <div className="livescore-soccer-main">
                    <div className="season-standings">
                        <div className="control-standing">
                            <span>{CommonFunctions.getLeagueName(currentLeagueStanding.league)}</span>
                            <select onChange={this.onChange} value={selectedLeagueId}>
                                {
                                    standings.map((standing, index) =>
                                        standing.standing.length > 0 &&
                                        <option value={standing.league.leagueId} key={index} >{CommonFunctions.getLeagueName(standing.league)}</option>)
                                }
                            </select>
                        </div>
                        <GridItem leagueStanding={currentLeagueStanding} />
                    </div>
                </div>
                <div className="view-more-block" onClick={event => this.handleClickStanding(event, currentLeagueStanding.league)}>
                    <a>Xem Thêm</a>
                </div>
            </div>
        );
    }
}