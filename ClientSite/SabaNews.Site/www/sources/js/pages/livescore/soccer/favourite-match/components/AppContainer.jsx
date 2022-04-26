import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import { Tabs, Tab } from 'react-bootstrap';
import FavoriteLeagues from '@jsroot/components/FavoriteLeagues';
import FavoriteMatches from '@jsroot/components/FavoriteMatches';
import MatchGrid from './MatchGrid';
import moment from 'moment';
import SignalR from './SignalR';
moment.updateLocale('vi', {
    weekdays: ['Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy']
});
moment.locale('vi');

export class AppContainer extends Component {
    constructor(props) {
        super(props);
    }
    componentDidMount() {
        this.props.getMajorLeagues();
        this.props.getMatches();
        this.props.getFavouriteMatches();
    }

    onUpdateFavouriteMatch = () => {
        this.props.getFavouriteMatches();
    }

    render() {
        console.log("favourite");
        const { majorLeagues, matches, postMatches, preMatches, liveMatches, favouriteMatches } = this.props;
        return (
            <div className="container livescore-container">
                <SignalR />
                <div className="livescore-soccer">
                    <div className="livescore-soccer-main">
                    <div className="livescore-league-header-detail">
                        <div className="livescore-league-header-detail-name">
                            Trận đấu quan tâm
                        </div>
                    </div>
                        <Tabs id="livescore-tab-league-detail"
                            className="live-score-nav"
                        >
                            <Tab eventKey="all" title="Tất cả">
                                {matches  && <MatchGrid sortAsc matches={matches} onUpdateFavouriteMatch={this.onUpdateFavouriteMatch}  />}
                            </Tab>
                            <Tab eventKey="live" title="Trực tiếp">
                                {liveMatches && <MatchGrid sortAsc matches={liveMatches} onUpdateFavouriteMatch={this.onUpdateFavouriteMatch} />}
                            </Tab>
                            <Tab eventKey="pre" title="Chưa diễn ra">
                                {preMatches && <MatchGrid sortAsc matches={preMatches} onUpdateFavouriteMatch={this.onUpdateFavouriteMatch} />}
                            </Tab>
                            <Tab eventKey="post" title="Đã diễn ra">
                                {postMatches && <MatchGrid sortAsc={false} matches={postMatches} onUpdateFavouriteMatch={this.onUpdateFavouriteMatch} />}
                            </Tab>
                        </Tabs>
                    </div>
                    <div className="livescore-soccer-extension">
                        <FavoriteMatches matches={favouriteMatches} />
                        <FavoriteLeagues leagues={majorLeagues} />
                    </div>
                </div>
            </div>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { majorLeagues, matches, postMatches, preMatches, liveMatches, favouriteMatches  } = state;
    return { majorLeagues, matches, postMatches, preMatches, liveMatches, favouriteMatches };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);