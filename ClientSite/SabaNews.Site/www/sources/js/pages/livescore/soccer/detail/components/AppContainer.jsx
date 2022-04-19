import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import SignalR from './SignalR';
import FunctionBar from './FunctionBar';
import { ActionCreators } from '../actions';
import MatchGenetalInfo from './MatchGeneralInfo';
import FavoriteMatches from '@jsroot/components/FavoriteMatches';

export class AppContainer extends Component {
    constructor(props) {
        super(props);
    }
    componentDidMount() {
        this.props.getMatchDetail();
    }

    render() {
        if (!this.props.match || !this.props.match.generalInfo ) {
            return null;
        }
        return (
            <>
                <div className="container livescore-container">
                <SignalR />
                    <div className="livescore-soccer">
                        <div className="livescore-soccer-main">
                            <MatchGenetalInfo match={this.props.match} isLiveMatch={this.props.isLiveMatch} />
                            <FunctionBar />
                        </div>
                        <div className="livescore-soccer-extension">
                            <FavoriteMatches />
                        </div>
                    </div>
                    
                </div>
            </>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { match, isLiveMatch } = state;
    return {
        match,
        isLiveMatch
    };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);