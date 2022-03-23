import React, { Component } from 'react';
import * as signalR from '@microsoft/signalr';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';

export class SignalR extends Component {
    constructor(props) {
        super(props);

        this.connection = null;
    }

    componentDidMount() {
        const protocol = new signalR.JsonHubProtocol();
        const transport = signalR.HttpTransportType.LongPolling;
        const options = {
            transport,
            logMessageContent: false,
            logger: signalR.LogLevel.None
        };

        this.connection = new signalR.HubConnectionBuilder()
            .withUrl(window.signalRHubUrl, options)
            .withHubProtocol(protocol)
            .withAutomaticReconnect()
            .build();

        this.connection.on('UpdateLiveSoccer', soccerMatches => {
            this.props.setLiveSoccerMatches(soccerMatches);
        });

        this.connection.start().then().catch(err => console.error(err.toString()));
    }

    componentWillUnmount() {
        this.connection.stop();
    }

    render() {
        return <span />;
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

export default connect(null, mapDispatchToProps)(SignalR);