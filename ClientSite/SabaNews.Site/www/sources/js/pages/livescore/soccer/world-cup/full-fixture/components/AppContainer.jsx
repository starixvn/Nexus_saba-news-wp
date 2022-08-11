import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import Stage from './Stage';
export class AppContainer extends Component {
    constructor(props) {
        super(props);
    }

    componentDidMount() {
        this.props.getMatches();
    }

    render() {
        const { league, matches } = this.props;
        return (
            <div className="livescore-soccer">
                { matches && matches.length > 0 && <Stage matches={matches} league={league} /> }
            </div>
        );
    }

}


const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { matches, league } = state;
    return { matches, league };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);