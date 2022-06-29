import React, { PureComponent } from 'react';
import { Container, Draggable } from 'react-smooth-dnd';
import TeamIcon from '@jsroot/components/Icon/TeamIcon';
import CommonFunctions from '@jsroot/common/common-functions';
import FavouriteLeagueIcon from '@jsroot/components/Icon/FavouriteLeagueIcon';
import Site from '@jsroot/common/site';

export const applyDrag = (arr, dragResult) => {
    const { removedIndex, addedIndex, payload } = dragResult;
    if (removedIndex === null && addedIndex === null) return arr;

    const result = [...arr];
    let itemToAdd = payload;

    if (removedIndex !== null) {
        itemToAdd = result.splice(removedIndex, 1)[0];
    }

    if (addedIndex !== null) {
        result.splice(addedIndex, 0, itemToAdd);
    }

    return result;
};

export const generateItems = (count, creator) => {
    const result = [];
    for (let i = 0; i < count; i++) {
        result.push(creator(i))
    }
    return result
}


// on mobile clean overflow when user touch
const cleanClasses = () => {
    document.body.className = '';
};
document.addEventListener('touchend', cleanClasses, false);
export default class Grid extends PureComponent {
    constructor(props) {
        super(props);
        this.state = {
            items: generateItems(100, i => ({
              id: i,
              data: `
                  Premier League ${i + 1}
                  `
            })),
            leagues: props.leagues
        }
    };

    componentDidUpdate(prevProps) {
        if(prevProps.leagues !== this.props.leagues) {
            this.setState({leagues: this.props.leagues});
        }
    }

    onUpdateFavLeague = league => {
        this.props.onUpdateFavLeague(league);
    };

    render() {
        const { leagues } = this.state;
        return (
            <Container lockAxis="y" onDrop={e => this.setState({ items: applyDrag(this.state.items, e) })}>
                {leagues && leagues.map(league => {
                    return (
                        <Draggable key={league.leagueId}>
                            <div className="draggable-item my-league-item">
                                <FavouriteLeagueIcon league={league} order={league.order}  onUpdateFavLeague={() => this.onUpdateFavLeague(league)}/>
                                <TeamIcon className="my-league-item-img" src={league.icon} />
                                <div className="my-league-item-name"> <span>{CommonFunctions.getLeagueName(league)}</span> </div>
                                <i className="my-league-item-drop icon-livescore icon-drop-drag"></i>
                            </div>
                        </Draggable>
                    );
                })}
            </Container>
        );
    }
}