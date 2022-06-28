import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import FavoriteMatches from '@jsroot/components/FavoriteMatches';
import RecentPosts from '@jsroot/components/RecentPosts';
import Breadscrum from '@jsroot/components/Breadscrum';
import { Container, Draggable } from 'react-smooth-dnd';


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

export const generateItems = (count , creator) => {
  const result = [];
  for (let i = 0; i < count; i ++){
    result.push(creator(i))
  }
  return result
}


// on mobile clean overflow when user touch
const cleanClasses = () => {
  document.body.className = '';
  };
document.addEventListener('touchend', cleanClasses, false);
export class AppContainer extends Component {
    constructor(props) {
        super(props);
        this.state = {
          items : generateItems(100, i => ({
            id : i,
            data : `
            Premier League ${i + 1}
            `
          }))
        }
    }

    render() {
        return (
            <div className="container livescore-container">
        <div className="livescore-soccer">
          <div className="livescore-soccer-main">
            <Breadscrum
              currentPage="Giải đấu quan tâm"
              currentPageUrl={window.location.href}
            />
            <div className="livescore-league-header-detail">
              <div className="livescore-league-header-detail-group">
                <div className="livescore-league-header-detail-name">
                  Giải đấu quan tâm
                </div>
              </div>
            </div>
            <div className='group-searchbar'>
                <div className="livescore-soccer-search-bar">
                <input
                    type="text"
                    placeholder="tìm kiếm giải đấu..."
                    className="livescore-soccer-searchbox form-control"
                />
                <span className="btn-search-match">
                    <i className="icon-livescore icon-search"></i>
                </span>
                </div>
            </div>

            <div className="my-league">

            <Container lockAxis="y" onDrop={e => this.setState({ items: applyDrag(this.state.items, e) })}>
              {this.state.items.map(p => {
                return (
                  <Draggable key={p.id}>
                    <div className="draggable-item my-league-item">
                      <i className="my-league-item-action icon-livescore icon-favourite-outline"></i>
                      <img
                        src="https://apimanager.ydssp.com/uploads/teamIcon/1200/90wgw8e03G0.png"
                        alt="logo-my-league"
                        className="my-league-item-img"
                      />
                      <div className="my-league-item-name"> <span>{p.data}</span> </div>
                      <i className="my-league-item-drop icon-livescore icon-drop-drag"></i>
                    </div>
                  </Draggable>
                );
              })}
            </Container>


              {/* <div className="my-league-item">
                <i className="my-league-item-action icon-livescore icon-favourite-outline"></i>
                <img
                  src="https://apimanager.ydssp.com/uploads/teamIcon/1200/90wgw8e03G0.png"
                  alt="logo-my-league"
                  className="my-league-item-img"
                />
                <div className="my-league-item-name"> <span>Premier League</span> </div>
                <i className="my-league-item-drop icon-livescore icon-drop-drag"></i>
              </div>
              <div className="my-league-item">
                <i className="my-league-item-action icon-livescore icon-favourite-outline"></i>
                <img
                  src="https://apimanager.ydssp.com/uploads/teamIcon/1200/90wgw8e03G0.png"
                  alt="logo-my-league"
                  className="my-league-item-img"
                />
                <div className="my-league-item-name"><span>Premier League</span></div>
                <i className="my-league-item-drop icon-livescore icon-drop-drag"></i>
              </div> */}
            </div>
          </div>
          <div className="livescore-soccer-extension">
            <FavoriteMatches />
            <RecentPosts />
          </div>
        </div>
      </div>
        );
    }
}


const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { matches } = state;
    return { matches };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);