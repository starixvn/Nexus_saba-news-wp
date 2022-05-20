import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import SignalR from './SignalR';
import Details from './Details';
import { ActionCreators } from '../actions';
import MatchGeneralInfo from './MatchGeneralInfo';
import FavoriteLeagues from '@jsroot/components/FavoriteLeagues';
import FavoriteMatches from '@jsroot/components/FavoriteMatches';
import CommonFunctions from '@jsroot/common/common-functions';

export class AppContainer extends Component {
    constructor(props) {
        super(props);
        this.state = {
            flagToggleFavLeagueIcon: false
        }
    }
    componentDidMount() {
        this.props.getMatchDetail();
        this.props.getFavLeagues();
    }

    onUpdateFavLeague = () => {
        this.props.setFavLeagues(CommonFunctions.getFavLeagues());
        this.setState({flagToggleFavLeagueIcon: !this.state.flagToggleFavLeagueIcon});
    }

    onToggleFavLeaguesIcon = () => {
        this.setState({flagToggleFavLeagueIcon: !this.state.flagToggleFavLeagueIcon});
    }

    render() {
        if (!this.props.match || !this.props.match.generalInfo) {
            return null;
        }
        return (
            <>
                <div className="container livescore-container">
                    <SignalR />
                    <div className="livescore-soccer">
                        <div className="livescore-soccer-main">
                            <MatchGeneralInfo match={this.props.match}
                                        flagToggleFavLeagueIcon={this.state.flagToggleFavLeagueIcon}
                                        onUpdateFavLeague={this.onUpdateFavLeague}/>
                            <Details />
                        </div>
                        <div className="livescore-soccer-extension">
                            <FavoriteMatches />
                            <FavoriteLeagues leagues={this.props.favLeagues} onToggleIcon={this.onToggleFavLeaguesIcon}/>
                        </div>
                    </div>
                </div>
            </>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { match, favLeagues } = state;
    return { match, favLeagues };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);