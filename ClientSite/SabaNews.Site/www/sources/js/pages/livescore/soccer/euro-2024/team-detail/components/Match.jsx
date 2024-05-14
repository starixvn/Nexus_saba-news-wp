import React from 'react';
import DateTimeService from '@jsroot/common/dateTimeService';
import CommonFunctions from '@jsroot/common/common-functions';
import TeamIcon from '@jsroot/components/Icon/TeamIcon';
import TeamScore from './TeamScore';
const Match = ({ matches }) => {
    const {key, childs} = matches;
    return (
        <div className="group-match-item">
            <div className="group-match-item-slogan">{ DateTimeService.convertFromUTC(key).format('DD-MM-YYYY') }</div>
            <div className="group-match-item">
                <div className="soccer-match-item">
                    <div className="group-team">
                        <div className="home-team">
                            <TeamIcon className="soccer-team-logo" src={CommonFunctions.getTeamIcon(childs[0].homeTeam)} alt={CommonFunctions.getTeamName(childs[0].homeTeam)} />
                            <div className="soccer-team-name">
                                {CommonFunctions.getTeamName(childs[0].homeTeam)}
                            </div>
                            <TeamScore match={matches} isHomeTeam />
                        </div>
                        <div className="soccer-team away-team">
                            <TeamIcon className="soccer-team-logo" src={CommonFunctions.getTeamIcon(childs[0].awayTeam)} alt={CommonFunctions.getTeamName(childs[0].awayTeam)}/>
                            <div className="soccer-team-name">
                                {CommonFunctions.getTeamName(childs[0].awayTeam)}
                                <TeamScore match={matches} />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Match;