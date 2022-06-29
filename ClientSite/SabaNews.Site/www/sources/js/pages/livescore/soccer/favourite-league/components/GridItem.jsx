import React, { PureComponent } from 'react';
import TeamIcon from '@jsroot/components/Icon/TeamIcon';
import CommonFunctions from '@jsroot/common/common-functions';
import FavouriteLeagueIcon from '@jsroot/components/Icon/FavouriteLeagueIcon';

export default class GridItem extends PureComponent {
    constructor(props) {
        super(props);
        this.state = {
            leagues: props.leagues
        }
    };

    componentDidUpdate(prevProps) {
        if (prevProps.leagues !== this.props.leagues) {
            this.setState({ leagues: this.props.leagues });
        }
    }

    onUpdateFavLeague = league => {
        this.props.onUpdateFavLeague(league);
    };

    render() {
        const { leagues } = this.state;
        return (
            <div>
                {leagues.map(league => {
                    return (
                        <div className="draggable-item my-league-item" key={league.leagueId}>
                            <FavouriteLeagueIcon league={league}
                                order={league.order}
                                onUpdateFavLeague={() => this.onUpdateFavLeague(league)}
                                className='my-league-item-action' />
                            <TeamIcon className="my-league-item-img" src={league.icon} />
                            <div className="my-league-item-name"> <span>{CommonFunctions.getLeagueName(league)}</span> </div>
                        </div>
                    );
                })}
            </div>
        );
    }
}