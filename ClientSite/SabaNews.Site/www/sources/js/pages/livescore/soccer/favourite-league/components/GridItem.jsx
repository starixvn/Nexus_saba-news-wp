import React, { PureComponent } from 'react';
import TeamIcon from '@jsroot/components/Icon/TeamIcon';
import CommonFunctions from '@jsroot/common/common-functions';
import FavouriteLeagueIcon from '@jsroot/components/Icon/FavouriteLeagueIcon';

export default class GridItem extends PureComponent {
    constructor(props) {
        super(props);
        this.state = {
            leagues: props.leagues
        };
    }

    componentDidUpdate(prevProps) {
        if (prevProps.leagues !== this.props.leagues) {
            this.setState({ leagues: this.props.leagues });
        }
    }

    onUpdateFavLeague = league => {
        this.props.onUpdateFavLeague(league);
    };

    handleClickLeague = (event, league) => {
        const className = event.target.className;
        if (className.includes('icon-livescore')) {
            return;
        }
        const url = CommonFunctions.getLeaguePageUrl(league);
        if (event.ctrlKey) {
            window.open(url, '_blank');
        } else {
            window.location = url;
        }
    };

    render() {
        const { leagues } = this.state;
        return (
            <div className="my-league">
                {leagues.map(league => {
                    return (
                        <div className="draggable-item my-league-item" key={league.leagueId}  onClick={event => this.handleClickLeague(event, league)}>
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