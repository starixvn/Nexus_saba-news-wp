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
        };
    }
    componentDidMount() {
        this.props.getMatchDetail();
        this.props.getFavLeagues();
        this.props.getLeagues();
    }

    onUpdateFavLeague = () => {
        this.props.setFavLeagues(CommonFunctions.getFavLeagues());
        this.setState({flagToggleFavLeagueIcon: !this.state.flagToggleFavLeagueIcon});
    };

    onToggleFavLeaguesIcon = () => {
        this.setState({flagToggleFavLeagueIcon: !this.state.flagToggleFavLeagueIcon});
    };

    checkIsEvent = (league, isEventPage) => {
        return CommonFunctions.isEvent(league, isEventPage);
    }

    render() {
        const { match, favLeagues, leagues } = this.props;
        if (!this.props.match || !this.props.match.generalInfo) {
            return null;
        }
        const homeTeamName = CommonFunctions.getTeamName(match.generalInfo.homeTeam);
        const awayTeamName = CommonFunctions.getTeamName(match.generalInfo.awayTeam);
        document.title = `${homeTeamName} vs ${awayTeamName}`;
        const isEvent = this.checkIsEvent(match.generalInfo.league, window.isEventPage);
        return (
            <>
                <div className="container livescore-container">
                    <SignalR />
                    <div className="livescore-soccer">
                        <div className="livescore-soccer-main">
                            <MatchGeneralInfo match={this.props.match}
                                        flagToggleFavLeagueIcon={this.state.flagToggleFavLeagueIcon}
                                        onUpdateFavLeague={this.onUpdateFavLeague}
                                        isEvent={isEvent}/>
                            <Details isEvent={isEvent}/>
                        </div>
                        {
                            !isEvent &&
                            <div className="livescore-soccer-extension">
                                <FavoriteMatches />
                                <FavoriteLeagues searchLeagues={leagues} leagues={favLeagues} onToggleIcon={this.onToggleFavLeaguesIcon}/>
                            </div>
                        }
                    </div>
                </div>
            </>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { match, favLeagues, leagues } = state;
    return { match, favLeagues, leagues };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);