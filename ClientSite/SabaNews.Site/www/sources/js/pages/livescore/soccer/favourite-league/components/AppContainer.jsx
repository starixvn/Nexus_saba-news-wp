import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import FavoriteMatches from '@jsroot/components/FavoriteMatches';
import RecentPosts from '@jsroot/components/RecentPosts';
import Breadscrum from '@jsroot/components/Breadscrum';
import { Container, Draggable } from 'react-smooth-dnd';
import TeamIcon from '@jsroot/components/Icon/TeamIcon';
import CommonFunctions from '@jsroot/common/common-functions';
import FavouriteLeagueIcon from '@jsroot/components/Icon/FavouriteLeagueIcon';
import Site from '@jsroot/common/site';
import Grid from './Grid';


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
export class AppContainer extends Component {
  constructor(props) {
    super(props);
    this.state = {
      items: generateItems(100, i => ({
        id: i,
        data: `
            Premier League ${i + 1}
            `
      })),
      searchKey: '',
      toggleIcon: false,
      searchLeagues: []
    }
  }

  componentDidMount() {
    this.props.getFavLeagues();
    this.props.getLeagues();
  }

  handleChange = event => {
    const searchKey = event.target.value;
    this.debounceSearchKeyInputChange(searchKey);
  };

  debounceSearchKeyInputChange = Site.debounce(searchKey => {
    this.setState({
      searchKey: searchKey
    }, () => {
      if (searchKey.length > 2) {
        const searchLeagues = this.props.leagues;
        let result = [];
        result = searchLeagues.filter(item =>
          this.checkSearchLeague(item.leagueNameEN, searchKey)
          || this.checkSearchLeague(item.leagueFullNameEN, searchKey)
          || this.checkSearchLeague(item.leagueNameVN, searchKey));
        this.setState({ searchLeagues: result, toggleIcon: !this.state.toggleIcon });
      } else if (searchKey.length === 0) {
        this.setState({ searchLeagues: [], toggleIcon: !this.state.toggleIcon });
      }
    });
  });

  checkSearchLeague = (fieldName, searchKey) =>  fieldName && (fieldName.toLowerCase().indexOf(searchKey.toLowerCase()) !== -1);

  isDefault = () => {
    const { searchKey, searchLeagues } = this.state;
    return searchKey.length < 3 && searchLeagues.length === 0;
  };
  render() {
    const { favLeagues, leagues } = this.props;
    const { searchKey, searchLeagues } = this.state;
    const isDefault = this.isDefault();
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
                  onChange={this.handleChange}
                />
                <span className="btn-search-match">
                  <i className="icon-livescore icon-search"></i>
                </span>
              </div>
            </div>

            <div className="my-league">
              {
                isDefault && <Grid leagues={favLeagues} />
              }
              {
                !isDefault && <Grid leagues={searchLeagues} />
              }
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
  const { favLeagues, leagues } = state;
  return { favLeagues, leagues };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);