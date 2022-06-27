import React, { Component } from "react";
import { connect } from "react-redux";
import { bindActionCreators } from "redux";
import { ActionCreators } from "../actions";
import FavoriteMatches from "@jsroot/components/FavoriteMatches";
import Breadscrum from "@jsroot/components/Breadscrum";

export class AppContainer extends Component {
  constructor(props) {
    super(props);
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

            <div className="my-league">sdakjdas</div>
          </div>
          <div className="livescore-soccer-extension">
            <FavoriteMatches />
          </div>
        </div>
      </div>
    );
  }
}

const mapDispatchToProps = (dispatch) =>
  bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = (state) => {
  const { matches } = state;
  return { matches };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);
