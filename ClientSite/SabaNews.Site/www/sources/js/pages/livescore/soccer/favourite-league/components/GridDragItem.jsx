import React, { PureComponent } from 'react';
import TeamIcon from '@jsroot/components/Icon/TeamIcon';
import CommonFunctions from '@jsroot/common/common-functions';
import FavouriteLeagueIcon from '@jsroot/components/Icon/FavouriteLeagueIcon';
import { DragDropContext, Droppable, Draggable } from 'react-beautiful-dnd';

export default class GridItem extends PureComponent {
    constructor(props) {
        super(props);
        this.state = {
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

    onDrop = dragData => {
        const { source, destination } = dragData;
        if(!source || !destination) {
            return;
        }
        const { leagues } = this.state;
        //const removedLeague = {...leagues[removedIndex], order: leagues[addedIndex].order};
        //const addedLeague = {...leagues[addedIndex], order: leagues[removedIndex].order};
        const itemToAdd = leagues.splice(source.index, 1)[0];
        leagues.splice(destination.index, 0, itemToAdd);
        // leagues[removedIndex]  = addedLeague;
        // leagues[addedIndex] = removedLeague;
        this.setState({leagues: [...leagues]});
        // let currentFavLeagues = CommonFunctions.getFavLeagues();
        // const removeFavIndex = currentFavLeagues.findIndex(item => item.leagueId === removedLeague.leagueId);
        // if(removeFavIndex > 0) {
        //     currentFavLeagues[removeFavIndex] = removedLeague;
        // }

        // const addedFavIndex = currentFavLeagues.findIndex(item => item.leagueId === addedLeague.leagueId);
        // if(addedFavIndex > 0) {
        //     currentFavLeagues[addedFavIndex] = addedLeague;
        // }
        // localStorage.setItem('favourite-leagues', JSON.stringify(currentFavLeagues));
    }

    render() {
        const { leagues } = this.state;
        if( this.props.leagues.length === 0) {
            return <div>Vui lòng nhập để tìm kiếm và thêm các giải đấu yêu thích vào đây.</div>
        }
        return (
            <DragDropContext onDragEnd={this.onDrop}>
                <Droppable droppableId='my-league'>
                    {(provided) => (
                        <div className="my-league" {...provided.droppableProps} ref={provided.innerRef}>
                            {leagues.map((league, index) => {
                                return (
                                    <Draggable key={index} draggableId={`${index}`} index={index}>
                                    {(provided) => (
                                        <div ref={provided.innerRef} {...provided.draggableProps} {...provided.dragHandleProps}>
                                            <div className="draggable-item my-league-item" >
                                                <FavouriteLeagueIcon
                                                    league={league}
                                                    order={league.order}
                                                    onUpdateFavLeague={() => this.onUpdateFavLeague(league)}
                                                    className='my-league-item-action'/>
                                                <TeamIcon className="my-league-item-img" src={league.icon} />
                                                <div className="my-league-item-name"> <span>{CommonFunctions.getLeagueName(league)}</span> </div>
                                                <i className="my-league-item-drop icon-livescore icon-drop-drag"></i>
                                            </div>
                                        </div>
                                    )}
                                </Draggable>
                                );
                            })}
                            {provided.placeholder}
                        </div>
                    )}
                </Droppable>
            </DragDropContext>
        );
    }
}