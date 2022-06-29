import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import FavoriteMatches from '@jsroot/components/FavoriteMatches';
import LastestNews from '@jsroot/components/LastestNews';
import Breadscrum from '@jsroot/components/Breadscrum';
import Site from '@jsroot/common/site';
import Grid from './Grid';
import CommonFunctions from '@jsroot/common/common-functions';


export class AppContainer extends Component {
  constructor(props) {
    super(props);
    this.state = {
      searchKey: '',
      toggleIcon: false,
      searchLeagues: []
    }
  }

  componentDidMount() {
    this.props.getFavLeagues();
    this.props.getLeagues();
    this.props.getLastestNews();
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
        const leagues = this.props.leagues;
        let result = [];
        result = leagues.filter(item =>
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

  onUpdateFavLeague = league => {
    const favLeagues  = [...this.props.favLeagues];
      const currentFavLeagues = CommonFunctions.getFavLeagues();
      const favLeague = currentFavLeagues.find(item => item.leagueId === league.leagueId);
      if (favLeague) {
          const index = favLeagues.findIndex(item => item.leagueId === league.leagueId);
          if (index < 0) {
              favLeagues.unshift(favLeague);
          }
      }
      this.props.setFavLeagues(favLeagues);
  }

  isDefault = () => {
    const { searchKey, searchLeagues } = this.state;
    return searchKey.length < 3 && searchLeagues.length === 0;
  };

  sortLeagues = leagues => {
    if(!leagues) return [];
    const items = [...leagues];
    return items.sort((item1, item2) => (item2.order - item1.order));
};
  render() {
    const { posts } = this.props;
    const { searchKey, searchLeagues } = this.state;
    const isDefault = this.isDefault();
    const favLeagues = this.sortLeagues(this.props.favLeagues);
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
                isDefault && <Grid leagues={favLeagues} onUpdateFavLeague={this.onUpdateFavLeague} isFav  />
              }
              {
                !isDefault && <Grid leagues={searchLeagues} searchKey={searchKey} onUpdateFavLeague={this.onUpdateFavLeague} />
              }
            </div>
          </div>
          <div className="livescore-soccer-extension">
            <FavoriteMatches />
            <LastestNews posts={posts} />
          </div>
        </div>
      </div>
    );
  }
}


const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
  const { favLeagues, leagues, posts } = state;
  return { favLeagues, leagues, posts };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);