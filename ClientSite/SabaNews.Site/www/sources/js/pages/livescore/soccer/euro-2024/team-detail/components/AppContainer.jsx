import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';

export class AppContainer extends Component {
    constructor(props) {
        super(props);
    }

    componentDidMount() {
        this.props.getTeamData(window.teamId);
    }

    render() {
        const { matches, players, team } = this.props;
        return (
            <div className='hello'>Hello</div>
        )
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { matches, players, team } = state;
    return { matches, players, team };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);