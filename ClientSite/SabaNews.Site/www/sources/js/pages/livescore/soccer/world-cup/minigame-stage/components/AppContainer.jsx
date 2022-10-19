import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';

export class AppContainer extends Component {
    constructor(props) {
        super(props);
    }
    componentDidMount() {
        this.props.getMatches();
        this.props.getVotes();
    }

    render() {
        const {matches, stages, votes} = this.props;
        console.log(matches, stages, votes);
        return (
            <div>
                <h1>Hello</h1>
            </div>
        )
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { matches, stages, votes } = state;
    return { matches, stages, votes };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);