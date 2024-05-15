import React, { PureComponent } from 'react';
import DateTimeService from '@jsroot/common/dateTimeService';
import CommonFunctions from '@jsroot/common/common-functions';
import TeamIcon from '@jsroot/components/Icon/TeamIcon';
import MatchStage from '@jsroot/components/MatchStage';
import TeamScore from './TeamScore';
export default class LiveScore extends PureComponent {
    constructor(props) {
        super(props);
    }
    render () {
        const { matches } =  this.props;
        return (
            <>
                {
                    matches && matches.map(item => (
                        <div className="soccer-match-grid" key={item.matchId}>
                            <div className="soccer-match-grid-item">
                                <div className="soccer-match-grid-item-header">{DateTimeService.convertFromUTC(item.startTime).format('DD-MM-YYYY')}</div>
                                <div className="soccer-match-grid-item-body">
                                    <div className="group-match-item">
                                        <div className="group-match-item">
                                            <div className="soccer-match-item">
                                                <div className="group-team">
                                                    <div className="soccer-team home-team">
                                                        <div className="soccer-team-name">
                                                            {CommonFunctions.getTeamName(item.homeTeam)}
                                                        </div>
                                                        <TeamIcon className="soccer-team-logo" src={CommonFunctions.getTeamIcon(item.homeTeam)} alt={CommonFunctions.getTeamName(item.homeTeam)} />
                                                        <TeamScore match={matches} isHomeTeam />
                                                    </div>
                                                    <MatchStage match={matches} />
                                                    <div className="soccer-team away-team">
                                                    <   TeamScore match={matches} />
                                                        <TeamIcon className="soccer-team-logo" src={CommonFunctions.getTeamIcon(item.awayTeam)} alt={CommonFunctions.getTeamName(item.awayTeam)}/>
                                                        <div className="soccer-team-name">
                                                            {CommonFunctions.getTeamName(item.awayTeam)}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="soccer-match-star-icon">
                                                    <i className="icon-live-right"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ))
                }
            </>
        )
    }
}   